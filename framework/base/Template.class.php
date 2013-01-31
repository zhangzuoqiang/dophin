<?php
/**
 * 模板类
 *
 * Created		: 2009-07-12
 * Modified		: 2012-09-11
 * @link		: http://www.dophin.co
 * @copyright	: (C) 2009-2012 Dophin 
 * @version		: 2.0.1
 * @author		: Joseph Chen (chenliq@gmail.com)
 */
class Template
{
	/**
	 * 模板标签左分隔符
	 * @var string
	 */
	public $delimiterLeft	= '{';
	/**
	 * 模板标签右分隔符
	 * @var string
	 */
	public $delimiterRight	= '}';
	/**
	 * 是否强制重新缓存
	 * @var boolean
	 */
	public $recache = 0;
	/**
	 * 模板主题目录
	 * @var string
	 */
	public $themePath = '';
	/**
	 * 模板编译缓存目录
	 * @var string
	 */
	public $cachePath = '';
	/**
	 * 当前模板文件
	 * @var string
	 */
	public $file = '';
	/**
	 * 当前模板编译后缓存文件
	 * @var string
	 */
	public $cacheFile = '';
	/**
	 * 模板文件后缀名
	 * @var string
	 */
	public $suffix = '.html.php';
	/**
	 * 所有模板文件编译时间
	 * @var array
	 */
	public $compileTime = array();
	/**
	 * 缓存模板文件编译时间配置文件路径
	 * @var string
	 */
	public $compileTimeCacheFile = '';
	/**
	 * 模板文件被包含列表
	 * @var array
	 */
	public $beIncluded = array();
	/**
	 * 缓存模板文件编译时间配置文件路径
	 * @var string
	 */
	public $beIncludedCacheFile = '';
	
	/**
	 * 构造函数
	 */
	public function __construct($tplName='', $themeName='default', $suffix='', $left='', $right='') 
	{
		if ($left != '') {
			$this->delimiterLeft = $left;
		}
		if ($right != '') {
			$this->delimiterRight = $right;
		}
		if ($suffix != '') {
			$this->suffix = $suffix;
		}
		if (is_dir($themeName)) {
			$this->themePath = $themeName;
			$this->cachePath = APP_PATH.'data'.DS.'viewcache'.DS.basename($themeName).DS;
		} else {
			if ($themeName) {
				$themeName = $themeName.DS;
			}
			$this->themePath = APP_PATH.'view'.DS.$themeName;
			$this->cachePath = APP_PATH.'data'.DS.'viewcache'.DS.$themeName;
		}
		
		if (!is_dir($this->cachePath)) {
			mkdir($this->cachePath, 0775, true);
		}
		if (is_file($tplName)) {
			$this->file = $tplName;
		} else {
			if (APP_DEBUG) {
				$filestr = ' ['.$tplName.']';
			}
			throw new Exception('The template file'.$filestr.' is not exist!');
		}
		$this->compileTimeCacheFile = $this->cachePath.'compileTime.inc.php';
		if (is_file($this->compileTimeCacheFile)) {
			$this->compileTime = include $this->compileTimeCacheFile;
		} else {
			$this->compileTime = array();
		}
		$this->beIncludedCacheFile = $this->cachePath.'beIncluded.inc.php';
		if (is_file($this->beIncludedCacheFile)) {
			$this->beIncluded = include $this->beIncludedCacheFile;
		} else {
			$this->beIncluded = array();
		}
	}
	
	/**
	 * 加载模板文件
	 * @param string $file
	 * @param array $vars
	 */
	public function load($vars=array(), $file='') 
	{
		if ($file=='') {
			$file = $this->file;
		}
		$replacement = array(
			$this->themePath	=> '',
			CORE_PATH.'view/'		=> ''
		);
		$this->cacheFile = $this->cachePath.strtr($file, $replacement);
		$this->autoCompile($file, $this->cacheFile);
		// 缓存编译文件存在则载入
		if (is_file($this->cacheFile)) {
			extract($GLOBALS);
			extract($vars);
			include $this->cacheFile;
		} else {//不存在报异常
			throw new Exception('The template file opened fail.');
		}
	}
	
	/**
	 * 根据文件修改时间,判断是否进行重编译
	 * @param string $file
	 * @param string $cacheFile
	 */
	public function autoCompile($file, $cacheFile) 
	{
		if (!APP_DEBUG && !$GLOBALS['_template_auto_compile'] && is_file($cacheFile)) {
			return false;
		}
		if (is_file($file)) {
			clearstatcache();
			$filetime = filemtime($file);
			$content = $this->getFileContent($file);
		} else {
			if (APP_DEBUG) {
				$filestr = '['.$file.']';
			}
			throw new Exception('The template file'.$filestr.' is not exist!');
		}
		
		// 包含其他模板文件,则遍历所有包含文件的更新时间
		if (false !== strpos($content, '<include')) {
			$pattern = '~<include[\s]+file="([^"]+)"[\s]*/>~';
			if (preg_match_all($pattern, $content, $m)) {
				foreach ($m[1] as $k=>$filename) {
					$filepath = dirname($this->file).DS.$filename.$this->suffix;
					$file = realpath($filepath);
					if ($file) {
						$_time = filemtime($file);
						if ($_time > $filetime) {
							$filetime = $_time;
						}
						$fileK = rtrim(str_replace($this->themePath, '', $file), '.html.php');
						$fileV = rtrim(str_replace($this->themePath, '', $this->file), '.html.php');
						// 保存文件被包含关系,当被包含模板更新时,需要更新所有的包含文件
						if (!isset($this->beIncluded[$fileK]) || !in_array($fileV, $this->beIncluded[$fileK])) {
							$this->beIncluded[$fileK][] = $fileV;
						}
					} else {
						if (APP_DEBUG) {
							$showFileStr = '['.$filepath.']';
						} else {
							$showFileStr = '';
						}
						throw new Exception('The included template file'.$showFileStr.' is not exist!');
					}
				}
			}
		}
		
		clearstatcache();
		
		$key = basename($cacheFile, $this->suffix);
		if (is_file($cacheFile) && isset($this->compileTime[$key])) {
			$cachetime = $this->compileTime[$key];
		} else {
			$dir = dirname($cacheFile);
			if (!is_dir($dir)) {
				mkdir($dir, 0775, true);
			}
			$cachetime = -1;
		}
		// 编译缓存文件更新时间早于模板时间,重新进行编译
		if (APP_DEBUG || $cachetime < $filetime) {
			if (isset($content)) {
				$content = $this->compile($content);
			} else {
				$content = $this->compileFile($file);
			}
			if (isset($content)) {
				$this->compileTime[$key] = time();
				$this->putFileContent($cacheFile, $content);
				$this->putFileContent($this->compileTimeCacheFile, '<?php return '.var_export($this->compileTime,true).';');
				$this->putFileContent($this->beIncludedCacheFile, '<?php return '.var_export($this->beIncluded,true).';');
			}
		}
		return true;
	}
	
	/**
	 * 编译模板内容
	 * @param string $file
	 * @throws Exception
	 */
	public function compile($content) 
	{
		// 包含其他模板文件
		if (false !== strpos($content, '<include'))
		{
			$pattern = '~<include[\s]+file="([^"]+)"[\s]*/>~';
			if (preg_match_all($pattern, $content, $m)) {
				foreach ($m[1] as $k=>$filename) {
					$file = realpath(dirname($this->file).DS.$filename.$this->suffix);
					if ($file) {
						$html = $this->compileFile($file);
						$content = str_replace($m[0][$k], $html, $content);
					} else {
						throw new Exception('The included template file is not exist!');
					}
				}
			}
		}
		// 引用包含其他模板文件
		if (false !== strpos($content, '<require'))
		{
			$pattern = '~<require[\s]+file="([^"]+)"[\s]*/>~';
			if (preg_match_all($pattern, $content, $m)) {
				foreach ($m[1] as $k=>$filename) {
					$file = realpath(dirname($this->file).DS.$filename.$this->suffix);
					if ($file) {
						$html = $this->compileFile($file);
						$rCacheFile = $this->cachePath.strtr($file, array($this->themePath=>''));
						$this->putFileContent($rCacheFile, $html);
						$path = strtr(dirname($this->cacheFile).'/', '\\', '/');
						$replacement = "<?php require(\"".$rCacheFile."\");?>";
						$content = str_replace($m[0][$k], $replacement, $content);
					} else {
						throw new Exception('The required template file is not exist!');
					}
				}
			}
		}
		// 编译if语句
		if (false !== strpos($content, '<if '))
		{
			$pattern = '~<if[\s]+(.+?)[\s]*>~';
			$content = preg_replace($pattern, '<?php if (\\1) { ?>', $content);
		}
		if (false !== strpos($content, '<elseif '))
		{
			$pattern = '~<elseif[\s]+(.+?)[\s]*/>~';
			$replace = "<?php } elseif (\\1) { ?>";
			$content = preg_replace($pattern, $replace, $content);
		}
		if (false !== strpos($content, '<else'))
		{
			$pattern = '~<else[\s]*/>~';
			$replace = "<?php } else { ?>";
			$content = preg_replace($pattern, $replace, $content);
		}
		if (false !== strpos($content, '</if>'))
		{
			$content = str_replace('</if>', '<?php } ?>', $content);
		}
		// 编译loop(foreach)语句
		if (false !== strpos($content, '<loop ') || false !== strpos($content, '<foreach '))
		{
			$pattern = '~<(?<tagName>loop|foreach)\s+(\S+)\s+(\S+)\s*>~s';
			$replace = '<?php if (is_array(\\2)) foreach (\\2 as \\3) { ?>';
			$content = preg_replace($pattern, $replace, $content);
			$pattern = '~<(?<tagName>loop|foreach)\s+(\S+)\s+(\S+)\s+(\S+)\s*>~s';
			$replace = '<?php if (is_array(\\2)) foreach (\\2 as \\3=>\\4) { ?>';
			$content = preg_replace($pattern, $replace, $content);
			$content = str_replace('</loop>', '<?php } ?>', $content);
			$content = str_replace('</foreach>', '<?php } ?>', $content);
		}
		// 编译for语句
		if (false !== strpos($content, '<for '))
		{
			$pattern = '~<for\s+(\d+)\s+(\d+)[\s]*>(.*?)\{\$([\w]+)\}(.*?)</for>~s';
			$replace = '<?php for($\\4=\\1;$\\4<=\\2;$\\4++) { ?>\\3<?php echo \$\\4; ?>\\5<?php }?>';
			$content = preg_replace($pattern, $replace, $content);
			$pattern = '~<for\s+(\d+)\s+(\d+)\s+(\d+)[\s]*>(.*?){\$([\w]+)}(.*?)</for>~s';
			$replace = '<?php for($\\5=\\1;$\\5<=\\2;$\\5+=\\3) { ?>\\4<?php echo \$\\5; ?>\\6<?php }?>';
			$content = preg_replace($pattern, $replace, $content);
		}
		// 预定义载入JS/CSS列表标签
		if (false !== strpos($content, '<preload '))
		{
			$pattern = '~<preload[\s]+type="([^"]+)"[\s]+list="([^"]+)"(?:[\s]+path="([^"]+)")*[\s]*/>~is';
			$replace = '<?php \$preload\\1=explode(",", "\\2");$\\1path="\\3"; ?>';
			$content = preg_replace($pattern, $replace, $content);
		}
		if (false !== strpos($content, '<load type="css" />'))
		{
			$code = <<<eot
<?php if (isset(\$preloadcss) && is_array(\$preloadcss)) foreach(\$preloadcss as \$v) {echo '<link href="'.\$csspath.\$v.'" rel="stylesheet" type="text/css" />';}?>
eot;
			$content = str_replace('<load type="css" />', $code, $content);
		}
		if (false !== strpos($content, '<load type="js" />'))
		{
			$code = <<<eot
<?php  if (isset(\$preloadjs) && is_array(\$preloadjs)) foreach(\$preloadjs as \$v) {echo '<script type="text/javascript" src="'.\$jspath.\$v.'"></script>';}?>
eot;
			$content = str_replace('<load type="js" />', $code, $content);
		}
		// load标签含有不标准空格数，上述代码无法替换，通过正则替换
		if (false !== strpos($content, '<load '))
		{
			$pattern = '~<load[\s]+type="([^"]+)"[\s]*/>~';
			$replace = '<?php if (isset($preload\\1) && is_array($preload\\1)) foreach($preload\\1 as $v) {'
						.'if ("\\1"=="js"){echo \'<script type="text/javascript" src="\'.$\\1path.$v.\'"></script>\';}'
						.'else{echo \'<link href="\'.$\\1path.$v.\'" rel="stylesheet" type="text/css" />\';}}?>';
			$content = preg_replace($pattern, $replace, $content);
		}
		// 编译导入JS标签
		if (false !== strpos($content, '<js '))
		{
			$pattern = '~<js[\s]+href="([^"]+)"[\s]*/>~';
			$replace = '<script type="text/javascript" src="\\1"></script>';
			$content = preg_replace($pattern, $replace, $content);
		}
		// 编译导入CSS标签
		if (false !== strpos($content, '<css '))
		{
			$pattern = '~<css[\s]+href="([^"]+)"[\s]*/>~';
			$replace = '<link href="\\1" rel="stylesheet" type="text/css" />';
			$content = preg_replace($pattern, $replace, $content);
		}
		
		// 编译写css内容到代码中
		if (false !== strpos($content, '<cssstyle '))
		{
			$pattern = '~<cssstyle[\s]+href="([^"]+)"[\s]*/>~';
			preg_match_all($pattern, $content, $m);
			foreach($m[1] as $k=>$v) {
				$cssFile = $this->themePath.'css'.DS.$m[1][$k];
				if (is_file($cssFile)) {
					$style = $this->getFileContent($cssFile);
					$content = str_replace($m[0][$k], '', $content);
					$content = str_replace('</head>', '<style>'.$style.'{__preCssStyle__}</style></head>', $content);
				} else {
					$content = str_replace($m[0][$k], '', $content);
					$content = str_replace('</head>', '<style>{__preCssStyle__}</style></head>', $content);
				}
			}
		}
		
		// 编译模板变量
		if (false !== strpos($content, $this->delimiterLeft.'$'))
		{
			$pattern = '~'.$this->delimiterLeft.'('
						.'\$[\w][\w]*'  //基本变量名
						//对象访问属性(属性可以是数组元素格式)
						.'(?:\-\>[\w]+(?:\[(?<quote1>["\'])[\w]+(\k<quote1>)\])*)*'
						.'(?:\[(?<quote>["\'])[\w]+(\k<quote>)\])*'  //包含数组
						.')'.$this->delimiterRight.'~';
			$replace = '<?php if(isset(\\1)){echo \\1;}?>';
			$content = preg_replace($pattern, $replace, $content);
		}
		// 编译类的静态属性
		$pattern = '~'.$this->delimiterLeft
					.'([A-Z][\w]*\:\:\$[\w]+(?:\[(?<quote>["\'])[\w]+(\k<quote>)\])*)'
					.$this->delimiterRight.'~';
		$replace = '<?php echo \\1;?>';
		$content = preg_replace($pattern, $replace, $content);
		// 编译类的静态方法
		$pattern = '~'.$this->delimiterLeft
					.'([A-Z][\w]*\:\:[\w]+\([^\)]*\)*)'
					.$this->delimiterRight.'~';
		$replace = '<?php echo \\1;?>';
		$content = preg_replace($pattern, $replace, $content);
		// 编译常量值
		$pattern = '~'.$this->delimiterLeft
					.'([A-Z_0-9]+)'
					.$this->delimiterRight.'~';
		$replace = '<?php if (defined("\\1")) {echo \\1;} else {echo "\\1";}?>';
		$content = preg_replace($pattern, $replace, $content);
		// 优化PHP代码
		$content = preg_replace('~\?\>([\s]*)\<\?php~s', '\\1', $content);
		// 编译函数输出
		if (false !== strpos($content, $this->delimiterLeft.'#'))
		{
			$pattern = '~'.$this->delimiterLeft.'#([\w]+\(.*?\))'.$this->delimiterRight.'~';
			$replace = '<?php echo \\1;?>';
			$content = preg_replace($pattern, $replace, $content);
		}

		// 编译写预定义css内容到代码中
		if (false !== stripos($content, '<preCssStyle>'))
		{
			$pattern = '~<preCssStyle>[\s]+<style>[\s]+(.*?)[\s]+</style>[\s]+</preCssStyle>[\s]*~is';
			preg_match($pattern, $content, $m);
			$content = preg_replace($pattern, '', $content);
			$content = str_replace('{__preCssStyle__}', $m[1], $content);
		} else {
			$content = str_replace('{__preCssStyle__}', '', $content);
		}
		// 编译页面加载后执行的代码
		if (false !== stripos($content, '<onloadjs>'))
		{
			$pattern = '~<onloadjs>[\s]+<script>[\s]+(.*?)[\s]+</script>[\s]+</onloadjs>[\s]*~is';
			preg_match($pattern, $content, $m);
			$content = preg_replace($pattern, '', $content);
			$content = str_replace('{__onloadjs__}', $m[1], $content);
		} else if (false !== stripos($content, '{__onloadjs__}')) {
			$content = str_replace('{__onloadjs__}', '', $content);
		}
		// 把样式内容重复标签去除
		if (false !== strpos($content, '</style><style>'))
		{
			$content = str_replace('</style><style>', '', $content);
		}
		$content = trim($content);
		return $content;
	}
	
	/**
	 * 编译模板文件
	 * @param string $file
	 * @throws Exception
	 */
	public function compileFile($file) 
	{
		$content = $this->getFileContent($file);
		return $this->compile($content);
	}
	
	/**
	 * 获取文件内容
	 * @param string $file
	 * @throws Exception
	 * @return string
	 */
	private function getFileContent($file) 
	{
		if( !($fp = fopen($file, 'r')) )
		{
			throw new Exception('The template file opened fail.');
		}
		$filesize = filesize($file);
		empty($filesize) && $filesize = 1;
		$content = fread($fp, $filesize);
		fclose($fp);
		return $content;
	}
	
	/**
	 * 写内容到文件
	 * @param string $file
	 * @param string $content
	 * @throws Exception
	 * @return string
	 */
	private function putFileContent($file, $content) 
	{
		if( !($fp = fopen($file, 'w')) )
		{
			throw new Exception('The template file opened fail.');
		}
		fwrite($fp, $content);
		fclose($fp);
		return $content;
	}
}