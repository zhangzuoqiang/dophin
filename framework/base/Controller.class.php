<?php
/**
 * 控制器抽象类
 * 
 * Created		: 2011-06-05
 * Modified		: 2012-08-23 
 * @link		: http://www.dophin.co
 * @copyright	: (C) 2011-2012 Dophin 
 * @version		: 1.0.0
 * @author		: Joseph Chen (chenliq@gmail.com)
 */
abstract class Controller
{
	
	/**
	 * 当前控制器调用的模板主题路径
	 * @var string
	 */
	public $themePath = '';
	/**
	 * 程序运行时添加的所有动态变量(加载模板时会导入到模板作用域中)
	 * @var unknown_type
	 */
	public $_vars = array();
	/**
	 * 当前控制器默认使用的模型
	 * @var object
	 */
	public $mo = null;
	/**
	 * 当前控制器名
	 * @var string
	 */
	public $co = '';
	/**
	 * 当前控制器调用操作方法
	 * @var string
	 */
	public $do = '';
	
	/**
	 * 抽象方法,所有子类都必须定义的方法
	 * 定义控制器默认的执行操作
	 */
	abstract public function index(); 
	
	/**
	 * 构造函数
	 */
	public function __construct(&$co, &$do)
	{
		$this->co = $co;
		$this->do = $do;
	}
	
	/**
	 * 未定义方法有模板页时可调用
	 * @param string $method
	 * @param array $arguments
	 * @return boolean
	 */
	public function __call($method, $arguments)
	{
		if (is_file($this->getThemeFile())) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 显示控制器对应的模板输出内容
	 * @param string $name
	 */
	public function display($name='') 
	{
		$engine = isset($GLOBALS['_template_engine']) ? $GLOBALS['_template_engine'] : 'php';
		if (is_file($name)) {
			$themeFile = $name;
		} else {
			$themeFile = $this->getThemeFile($name);
		}
		if (!is_file($themeFile)) {
			if (APP_DEBUG) {
				$filestr = ' ['.$themeFile.']';
			} else {
				$filestr = '';
			}
			$args = array(
				'{template_file}'	=> $filestr,
			);
			Core::throwError(
				'template_file_not_exist', 
				__FILE__, 
				__LINE__, 
				$args
			);
		}
		// 需要获取缓存冲区内容
		if (!empty($_GET['ob_html']))
		{
			ob_start();
		}
		if ($engine == 'php'){
			include $themeFile;
		} else if ($engine == 'dophin') {
			$suffix = isset($GLOBALS['_suffix']) ? $GLOBALS['_suffix'] : '';
			$left = isset($GLOBALS['_tpl_delimiter_left']) ? $GLOBALS['_tpl_delimiter_left'] : '';
			$right = isset($GLOBALS['_tpl_delimiter_right']) ? $GLOBALS['_tpl_delimiter_right'] : '';
			$tpl = new Template($themeFile, $this->themePath, $suffix, $left, $right);
			$tpl->load($this->_vars);
		} else {
			echo 'unknow template engine!';
		}
		// 输出内容到html静态文件
		if (!empty($_GET['make_html']))
		{
			if (!empty($_GET['html_file']))
			{
				$htmlFile = HTML_PATH.$_GET['html_file'];
			} else if (isset($GLOBALS['html_file'])) {
				$htmlFile = HTML_PATH.$GLOBALS['html_file'];
			} else if ($this->html_cache_file) {
				$htmlFile = HTML_PATH.$this->html_cache_file;
			} else {
				if (!empty($_GET['id']) && !ctype_digit($_GET['id']))
				{
					$htmlFile = HTML_PATH.$this->co.'_'.$this->do.'_'.$_GET['id'].'.html';
				} else {
					$htmlFile = HTML_PATH.$this->co.'_'.$this->do.'.html';
				}
			}
			$dir = dirname($_GET['html_file']);
			if (!is_dir($dir))
			{
				mkdir($dir, 0775, true);
			}
			$html = ob_get_contents();
			file_put_contents($_GET['html_file'], $html);
			if ($_GET['html_display'])
			{
				ob_end_flush();
			} else {
				ob_end_clean();
				return true;
			}
		}
		if ($GLOBALS['_output_process_time']) {
			echo microtime(1) - APP_BEGIN_TIME;
		}
		// 显示完页面不执行后面代码
		exit;
	}
	
	/**
	 * 输出/返回JSON数据
	 * @param string $msg
	 * @param int $status
	 * @param array|string|int $data
	 * @param boolean $output
	 */
	public function json($msg='', $status=1, $data=null, $output=true) 
	{
		$result = array('status' => $status);
		if (!is_null($data)) {
			$result['data'] = $data;
		}
		if ($msg) {
			$result['msg'] = $msg;
		}
		$json = json_encode($result);
		if ($output) {
			header('content-type:application/json;charset=utf-8');
			echo $json;
			exit;
		} else {
			return $json;
		}
	}

	/**
	 * 操作成功,提示页面
	 * @param string $msg
	 * @param string $redirectUrl
	 */
	public function success($msg, $redirectUrl='', $isAjax=false) 
	{
		$this->pageTitle = Core::getLang('handle_success');
		if (!$redirectUrl) {
			$redirectUrl = $_SERVER["HTTP_REFERER"];
		}
		$this->redirect($redirectUrl, 1, $msg, $isAjax);
	}
	
	/**
	 * 操作失败,提示页面
	 * @param string $msg
	 * @param string $redirectUrl
	 */
	public function failure($msg, $redirectUrl='', $isAjax=false) 
	{
		$this->pageTitle = Core::getLang('handle_failed');
		$this->redirect($redirectUrl, 0, $msg, $isAjax);
	}
	
	/**
	 * 发送404HEADER信息
	 */
	public function header404()
	{
		if (substr(PHP_SAPI, 0, 3) == 'cgi')
		{
			header('Status: 404 Not Found');
		} else {
			header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
		}
		exit;
	}
	
	/**
	 * 页面跳转
	 * @param string $url
	 * @param int $status
	 * @param string $msg
	 * @param boolean $isAjax
	 */
	protected function redirect($url='', $status=1, $msg='', $isAjax=false) 
	{
		if ($isAjax || $this->isAjax()) {
			$this->json($msg, $status);
		}
		if (!$url) {
			$url = 'javascript:history.back();';
			$this->goBack = true;
		} else {
			header('refresh:2;url='.$url);
			$this->goBack = false;
		}
		$this->url = $url;
		$this->msg = $msg;
		$this->display(CORE_PATH.'view/msg.html.php');
	}
	
	/**
	 * 获取当前控制器方法对应的模板文件
	 * @param string $name
	 * @return string
	 */
	protected function getThemeFile($name='')
	{
		if (defined('VIEW_PATH')) {
			$themePath = VIEW_PATH;
		} else if (!$this->themePath) {
			$themePath = APP_PATH.'view'.DS.$GLOBALS['_theme_name'].DS;
		} else {
			$themePath = $this->themePath;
		}
		if ($name) {
			$curDir = (Core::$co=='index') ? '' : Core::$co.DS;
			$themeFile = $themePath.$curDir.$name.$GLOBALS['_tpl_suffix'];
		} else if (Core::$do == 'index') {
			$themeFile = $themePath.Core::$co.$GLOBALS['_tpl_suffix'];
			if (!is_file($themeFile)) {
				$themeFile = $themePath.Core::$co.DS.Core::$do.$GLOBALS['_tpl_suffix'];
			}
		} else if (Core::$co == 'index') {
			$themeFile = $themePath.Core::$do.$GLOBALS['_tpl_suffix'];
			if (!is_file($themeFile)) {
				$themeFile = $themePath.Core::$co.DS.Core::$do.$GLOBALS['_tpl_suffix'];
			}
		} else {
			$themeFile = $themePath.Core::$co.DS.Core::$do.$GLOBALS['_tpl_suffix'];
		}
		$this->themePath = $themePath;
		return $themeFile;
	}
	
	/**
	 * 判断当前请求是否AJAX方式
	 * HTTP_X_REQUESTED_WITH 只有客户端用JQuery框架ajax请求才有这个参数
	 */
	protected function isAjax() {
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) ) {
			if('xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])) {
				return true;
			}
		}
		// 表单提交或URL有对应的AJAX请求参数标志
		if(!empty($_POST['IS_AJAX_REQUEST']) || !empty($_GET['IS_AJAX_REQUEST'])) {
			return true;
		}
		return false;
	}
	
	/**
	 * 设置控制器变量
	 * @param string $var
	 * @param mixed $val
	 */
	public function __set($var, $val)
	{
		$this->_vars[$var] = $val;
	}
	
	/**
	 * 获取控制器变量
	 * @param string $var
	 */
	public function __get($var) 
	{
		if (isset($this->_vars[$var])) {
			return $this->_vars[$var];
		} else {
			return null;
		}
	}
	
	
}