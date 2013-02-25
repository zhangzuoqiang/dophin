<?php
/**
 * Core file.
 *
 * Created		: 2009-07-12
 * Modified		: 2012-09-12 
 * @link		: http://www.dophin.co
 * @copyright	: (C) 2007-2012 Dophin 
 * @version		: 3.0.0
 * @author		: Joseph Chen <chenliq@gmail.com>
 */
/**
 * 载入核心文件之前必须先定义应用路径
 */
if (!defined('APP_PATH')) {exit;}
/**
 * 程序开始执行时间
 */
define('APP_BEGIN_TIME', microtime(true));
/**
 * 常量 DIRECTORY_SEPARATOR 的缩写
 */
define('DS', DIRECTORY_SEPARATOR);
/**
 * 定义框架的硬盘绝对路径
 */
defined('CORE_PATH') or define('CORE_PATH',dirname(__FILE__).DS);

class Core
{
	/**
	 * 全局配置
	 * @var array
	 */
	public static $cfg = array();
	/**
	 * 当前程序使用的语言包数组
	 * @var string
	 */
	public static $lang = array();
	/**
	 * 当前控制器目录
	 * @var string
	 */
	public static $controllerPath = '';
	/**
	 * 当前控制器
	 * @var string
	 */
	public static $co = '';
	/**
	 * 当前控制器操作指令
	 * @var string
	 */
	public static $do = '';
	/**
	 * 无效的控制器请求方法
	 * @var array
	 */
	public static $invalidDo = array('__construct', '__call', 'display', 'json');
	
	/**
	 * 出错的文件路径
	 * @var string
	 */
	protected static $errfile = '';
	/**
	 * 出错的文件行号位置
	 * @var string
	 */
	protected static $errline = '';
	
	/**
	 * 初始化程序
	 */
	public static function init()
	{
		if (defined('CO_PATH'))
		{
			self::$controllerPath = CO_PATH;
		} else {
			self::$controllerPath = APP_PATH.'controller'.DS;
		}
		// 设置程序编码
		mb_internal_encoding($GLOBALS['_charset']);
		// 输出内容格式
		header('content-type:text/html;charset='.$GLOBALS['_charset']);
		// 初始化错误处理函数
		self::initSystemHandlers();
		// 加载框架核心语言包
		self::loadCoreLang();
		// 初始化用户信息
		User::init();
	}
	
	/**
	 * 入口函数
	 * @param string $co
	 * @param string $do
	 * @return boolean
	 */
	public static function main($co=null, $do=null)
	{
		self::init();
		
		if (!$co && defined('NO_AUTO_DISPATCH') && NO_AUTO_DISPATCH)
		{
			return true;
		}
		
		self::parseController();
		
		self::dispatch();
		
		return true;
	}
	
	public static function parseController()
	{
		switch ($GLOBALS['_parse_controller_type']) 
		{
			case 'PATH_INFO':
				self::parseControllerFromPathInfo();
			break;
			
			case 'URL_PARAMS':
				self::parseControllerFromGetParams();
			break;
			
			default:
				self::throwError(
						'incorrect_config',
						__FILE__,
						__LINE__,
						$args
				);
			break;
		}
		

		// 预先载入Controller基类，不需要自动加载
		include CORE_PATH.self::$coreClass['Controller'];
		$file = self::$controllerPath.self::$co.'.co.php';
		if (is_file($file))
		{
			include $file;
		} else {
			$args = array(
					'{controller}'	=> self::$co,
			);
			self::throwError(
					'controller_not_exist',
					__FILE__,
					__LINE__,
					$args
			);
		}
		
		
		self::$co = strtolower(self::$co);
	}
	
	/**
	 * 调遣相应的控制器
	 * @param string $name
	 */
	public static function dispatch($name='') 
	{
		$controllerClass = 'Controller_'.ucfirst(self::$co);
		$controller = new $controllerClass(self::$co, self::$do);
		if (in_array(self::$do, Core::$invalidDo))
		{
			self::throwError(
					'invalid_request', 
					__FILE__, 
					__LINE__
			);
		} else if (method_exists($controller, self::$do)) {
			if (method_exists($controller, 'initialize'))
			{
				call_user_func_array(array($controller, 'initialize'), array());
			}
			$return = call_user_func_array(array($controller, self::$do), array());
			// 自动加载模板进行显示(除非action返回false)
			if ($return !== false)
			{
				$controller->display();
			}
		} else {
			self::throwDoMethodError(__LINE__);
		}
		return true;
	}
	
	/**
	 * 
	 * @return boolean
	 */
	private static function parseControllerFromPathInfo()
	{
		if (empty($_SERVER['REQUEST_URI']) || $_SERVER['REQUEST_URI'] == '/')
		{
			self::$co = 'index';
			self::$do = 'index';
			return true;
		}
		if (false !== strpos($_SERVER['REQUEST_URI'], '-'))
		{
			$REQUEST_URI = strtr($_SERVER['REQUEST_URI'], '-', '/');
		} else {
			$REQUEST_URI = $_SERVER['REQUEST_URI'];
		}
		if (0 === strpos($REQUEST_URI, '/')) {
			$REQUEST_URI = substr($REQUEST_URI, 1);
		}
		if (false === strpos($REQUEST_URI, '/') && ctype_alpha($REQUEST_URI))
		{
			self::$co = $REQUEST_URI;
		} else if (preg_match('/[^\/]+\.php/i', $REQUEST_URI))	{
			self::$co = 'index';
			self::$do = 'index';
			return true;
		} else {
			$router = explode('/', $REQUEST_URI);
			self::$co = $router[0];
			if (isset($router[1]))
			{
				if (ctype_alnum($router[1]))
				{
					$do = $router[1];
				} else if (ctype_digit($router[1])) {
					if (empty($_GET['id']))
					{
						$_GET['id'] = $router[1];
					}
				}
			}
			if (count($router) > 2)
			{
				unset($router[0], $router[1]);
				foreach ($router as $k=>$v)
				{
					if (!empty($_GET[$k]))
					{
						continue;
					}
					$_GET[$k] = $v;
				}
			}
		}
		if (isset($do))
		{
			self::$do = $do;
		} else {
			self::$do = 'index';
		}
		return true;
	}
	
	/**
	 * 从URL参数获取控制器入口
	 */
	private static function parseControllerFromGetParams()
	{
		
	}
	
	/**
	 * 抛出控制器方法不存在的错误
	 */
	private static function throwDoMethodError($line=null, $file=null)
	{
		if (!$file)
		{
			$file = __FILE__;
		}
		if (!$line) {
			$line = __LINE__;
		}
		$args = array(
			'{method}'	=> self::$do,
		);
		self::throwError('controller_method_not_exist', $file, $line, $args);
	}

	/****************************** 公共方法 ******************************/
	/**
	 * 导入配置
	 * @throws Exception
	 */
	public static function C($cfgName)
	{
		if (!$cfgName)
		{
			throw new Exception('Arguments is empty!');
		}
		if (false !== strpos($cfgName, ','))
		{
			$args = explode(',', $cfgName);
		} else if (is_array($cfgName)) {
			$args = $cfgName;
		} else {
			$args = array($cfgName);
		}
		$cfg = array();
		foreach ($args as $cfgName) 
		{
			$file = APP_PATH.'config'.DS.$cfgName.'.cfg.php';
			if (is_file($file))
			{
				$cfg = array_merge($cfg, include($file));
			}
		}
		return $cfg;
	}
	
	/**
	 * 设置缓存配置信息
	 * @param string $cfgName
	 * @param mixed $value
	 */
	public static function setC($cfgName, $value) 
	{
		$file = APP_PATH.'config'.DS.$cfgName.'.cfg.php';
		$dir = dirname($file);
		if (!is_dir($dir))
		{
			mkdir($dir, 0775, true);
		}
		if (is_array($value) || is_object($value)) {
			$value = var_export($value, true);
		}
		self::write($file, $value);
		return true;
	}
	
	/**
	 * 默认的系统自动加载类文件的方法
	 * @param string $className
	 */
	public static function autoload($className) 
	{
		if (isset(self::$coreClass[$className]))
		{
			$filename = CORE_PATH . DS . self::$coreClass[$className];
		} else if (0 === strpos($className, 'Model_')) {// 模型类
			$className = ucfirst($className);
			$filename = APP_PATH.'model/'.substr($className, 6).'.php';
		} else if (0 === strpos($className, 'Controller_')) {// 模型类
			$filename = APP_PATH.'controller/'.lcfirst(substr($className, 11)).'.co.php';
		} else if (false !== strpos($className, '_')) {// 下划线分隔目录和类
			$className = lcfirst($className);
			$filename = APP_PATH.'component/'.strtr($className, '_', '/').'.class.php';
		} else {
			$filename = APP_PATH.'component/'.$className.'.class.php';
			if (!is_file($filename)) {
				$filename = CORE_PATH.'component/'.$className.'.class.php';
			}
			if (!is_file($filename)) {
				return false;
			}
		}
		if ($filename && is_file($filename)) {
			include $filename;
		} else {
			return false;
		}
	}

	/**
	 * 注册一个新的自动加载函数
	 * @param callback $callback PHP回调函数(函数名或array($className,$methodName)).
	 * @param boolean $append 是否将自动加载函数添加到默认的加载方法后.
	 */
	public static function registerAutoloader($callback, $append=false)
	{
		if($append)
		{
			spl_autoload_register($callback);
		} else {
			spl_autoload_unregister(array('self', 'autoload'));
			spl_autoload_register($callback);
			spl_autoload_register(array('self', 'autoload'));
		}
	}
	
	/**
	 * 创建URL
	 */
	public static function createUrl($router)
	{
		return $router;
	}

	/**
	 * 页面跳转
	 */
	public static function redirect($url)
	{
		header('Location:'.$url);
		exit;
	}
	
	/**
	 * 初始化错误处理函数
	 */
	public static function initSystemHandlers()
	{
		set_exception_handler('Core::handleException');
		set_error_handler('Core::handleError');
	}
	
	/**
	 * 异常处理
	 * @param object $exception
	 */
	public static function handleException($exception)
	{
		restore_exception_handler();
		$message = self::getLang($exception->getMessage());
		echo '<pre>';
		var_export($exception);
		echo '</pre>';
		exit;
	}
	
	/**
	 * 错误处理
	 * @param int $errno
	 * @param string $errstr
	 * @param string $errfile
	 * @param int $errline
	 */
	public static function handleError($errno, $errstr, $errfile, $errline)
	{
		restore_error_handler();
		$exit = true;
		switch ($errno)
		{
			case E_USER_ERROR:
				echo 'Error:	';
				break;
			case E_USER_WARNING:
				echo 'Warning:	';
				break;
			case E_USER_NOTICE:
				echo 'Notice:	';
				$exit = false;
				break;
			case E_NOTICE:
				echo 'Notice:	';
				$exit = false;
				break;
			case E_PARSE:
			case E_COMPILE_ERROR:
			case E_COMPILE_WARNING:
				echo 'Fatal Error:	';
				$exit = false;
				break;
			default:
				echo 'Error:';
				break;
		}
		echo $errstr;
		if ($GLOBALS['_debug'])
		{
			echo '	in [ ';
			if (self::$errfile) {
				echo self::$errfile;
			} else {
				echo $errfile;
			}
			echo ' ] ';
		} else {
			echo ' in ';
			echo ' [unknow file] ';
		}
		echo ' on line ';
		if (self::$errline)
		{
			echo self::$errline;
		} else {
			echo $errline;
		}
		if ($exit) {
			exit;
		}
	}
	
	/****************************** 工具类方法 ******************************/
	/**
	 * 是否ajax请求
	 */
	public static function isAjaxRequest()
	{
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest';
	}
	
	/**
	 * 抛出一个错误
	 * @param string $str
	 * @param array $replacement
	 */
	public static function throwError($message, $file, $line, $replacement=array(), $lvl=E_USER_ERROR)
	{
		if (empty($replacement)) $replacement = array();
		$message = self::getLang($message);
		$message = strtr($message, $replacement);
		self::$errfile = $file;
		self::$errline = $line;
		trigger_error($message, $lvl);
	}
	
	/**
	 * 抛出一个异常
	 * @param string $str
	 * @param array $replacement
	 */
	public static function throwException($message, $file, $line, $replacement=array(), $lvl=E_USER_ERROR)
	{
		if (empty($replacement)) $replacement = array();
		$message = self::getLang($message);
		$message = strtr($message, $replacement);
		self::$errfile = $file;
		self::$errline = $line;
		throw new Exception($message, $code, $previous);
	}
	
	/**
	 * 获取字符串描述对应的语言内容
	 * @param string $str
	 */
	public static function getLang($str, $replacement=array()) 
	{
		if (isset(self::$lang[$str]))
		{
			$msg = self::$lang[$str];
		} else {
			$msg = $str;
		}
		if (isset($replacement[0])) {
			$msg = sprintf($msg, $replacement);
		} else if ($replacement) {
			$msg = strtr($msg, $replacement);
		}
		return $msg;
	}
	
	/**
	 * 加载核心语言文件
	 */
	public static function loadCoreLang() 
	{
		if (is_dir($dir = CORE_PATH.'Messages/'.$GLOBALS['_lang'].'/'))
		{
			self::$lang = array_merge(include $dir.'core.php', self::$lang);
		}
	}
	
	/**
	 * 加载项目的语言文件
	 * @param string $name
	 */
	public static function loadLang($name)
	{
		$file = APP_PATH.'lang/'.$GLOBALS['_lang'].'/'.$name.'.lang.php';
		if (is_file($file))
		{
			self::$lang = array_merge(self::$lang, include $file);
		}
		$GLOBALS['_L'] = self::$lang;
	}
	
	/**
	 * 写内容到文件中
	 * @param string $file
	 * @param string $content
	 * @param string $type
	 * @param boolean $return
	 */
	public static function write($file, $content, $type='php', $return=true) 
	{
		if ($type == 'php')
		{
			if ($return)
			{
				$return = ' return ';
			} else {
				$return = '';
			}
			$content = '<?php'.$return.$content.';';
		}
		$fp = fopen($file, 'w');
		if (is_writeable($file))
		{
			fwrite($fp, $content);
		}
		fclose($fp);
	}
	
	/**
	 * 系统类名与文件路径对应
	 * @var array
	 */
	protected static $coreClass = array (
		'Controller'			=> 'base/Controller.class.php',
		'Model'					=> 'base/Model.class.php',
		'Template'				=> 'base/Template.class.php',
			
		'Cache'					=> 'cache/Cache.class.php',
		'Mem'					=> 'cache/Mem.class.php',
		'CacheMemcache'			=> 'cache/CacheMemcache.class.php',
		'CacheRedis'			=> 'cache/CacheRedis.class.php',
			
		'Db'					=> 'db/Db.class.php',
		'Mysql'					=> 'db/Mysql.class.php',
		'Sqlite'				=> 'db/Sqlite.class.php',
			
		'GIFEncoder'			=> 'lib/GIFEncoder.class.php',
		
		'Arr'					=> 'utils/Arr.class.php',
		'Fso'					=> 'utils/Fso.class.php',
		'IdCard'				=> 'utils/IdCard.class.php',
		'Page'					=> 'utils/Page.class.php',
		'Redis'					=> 'utils/Redis.class.php',
		'ReflectionClassExt'	=> 'utils/ReflectionClassExt.class.php',
		'String'				=> 'utils/String.class.php',
		'UMath'					=> 'utils/UMath.class.php',
// 		'Validation'			=> 'utils/Validation.class.php',
			
		'Ip'					=> 'net/Ip.class.php',
		'Net'					=> 'net/Net.class.php',
		'Session'				=> 'net/Session.class.php',
		'UploadFile'			=> 'net/UploadFile.class.php',
		'Url'					=> 'net/Url.class.php',
	);
}

/**
 * 获取获取某个配置项的值
 * @param string|array $cfgName
 * @param mixed $cfgVal
 */
function C($cfgName, $cfgVal=null) 
{
	if (is_array($cfgName))
	{
		foreach ($cfgName as $k=>$v) {
			$GLOBALS[$k] = $v;
		}
		return true;
	} else {
		if (is_null($cfgVal))
		{
			if (isset($GLOBALS[$cfgName]))
			{
				return $GLOBALS[$cfgName];
			} else {
				return false;
			}
		} else {
			$GLOBALS[$cfgName] = $cfgVal;
			return true;
		}
	}
}



//初始化配置
include CORE_PATH.'config/core.cfg.php';
include APP_PATH.'config/main.cfg.php';

/**
 * 定义程序是否调试模式
 */
defined('APP_DEBUG') or define('APP_DEBUG', $GLOBALS['_debug']);
$_REQUEST = array_merge($_COOKIE, $_GET, $_POST);

// 类自动加载处理方法
spl_autoload_register(array('Core', 'autoload'));
if ($_session_user) {
	$session = new Session($_session_type);
}