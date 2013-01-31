<?php
/**
 * 上传文件操作
 *
 * Created		: 2012-06-21
 * Modified		: 2012-06-21
 * @link		: http://www.dophin.co
 * @copyright	: (C) 2012 Dophin 
 * @version		: 0.1.0
 * @author		: Joseph Chen (chenliq@gmail.com)
 */
class UploadFile
{
	/**
	 * 最大上传文件大小(默认2M)
	 * @var int
	 */
	public $maxSize = 2097152;
	/**
	 * 允许上传文件的后缀类型
	 * @var array
	 */
	public $allowExts = array('jpg', 'gif', 'png');
	/**
	 * 上传文件保存的路径
	 * @var string
	 */
	public $savePath = '';
	/**
	 * 上传文件名生成规则(任意不带参数的函数名/类的静态方法/字符串)
	 * 如果是默认的Fso::randFileName方法，可以带参数，规则为"#参数1#参数2"
	 * @var array|string
	 */
	public $saveRule = '';
	/**
	 * 上传的文件列表
	 * @var array
	 */
	public $fileList = array();
	/**
	 * 上传的文件数量
	 * @var int
	 */
	public $fileNum = 0;
	/**
	 * 是否出错
	 * @var boolean
	 */
	public $error = false;
	/**
	 * 出错的字段
	 * @var string
	 */
	public $errorField = '';
	/**
	 * 出错的文件
	 * @var string
	 */
	public $errorFile = '';
	/**
	 * 出错的提示信息
	 * @var string
	 */
	public $errorMsg = '';
	
	
	/**
	 * 构造函数
	 * @param string $savePath
	 * @param array $options
	 */
	public function __construct($options=null)
	{
		if (isset($options['max_size'])) {
			$this->maxSize = $options['max_size'];
		}
		if (isset($options['save_path'])) {
			$this->savePath = $options['save_path'];
		}
		if (isset($options['save_rule'])) {
			$this->saveRule = $options['save_rule'];
		}
	}
	
	/**
	 * 上传文件
	 */
	public function upload() 
	{
		if ($this->error) {
			return false;
		}
		$saveList = array();
		/**
		 * 循环检测文件
		 */
		foreach ($_FILES as $field => $item) {
			if (is_array($item['name'])) {
				$this->fileList[$field] = array();
				foreach ($item['name'] as $k=>$file) {
					if (UPLOAD_ERR_NO_FILE == $item['error'][$k]) {
						continue;
					}
					$this->fileNum++;
					// 文件是否成功上传
					if (UPLOAD_ERR_OK != $item['error'][$k]) {
						$this->setUploadErrorMsg($item['error'][$k], $file);
						$this->error = true;
						return false;
					}
					// 文件大小检测
					if (!$this->checkFileSize($field, $file, $item['size'][$k])) {
						return false;
					}
					// 文件格式是否合法
					if (!$this->checkExt($field, $file, $item['tmp_name'][$k])) {
						return false;
					}
					// 文件上传路径验证
					if (!($path=$this->getSavepath($field))) {
						return false;
					}
					$savename = $this->generateSavename($field).'.'.Fso::getExt($file);
					$saveList[] = array($item['tmp_name'][$k], $path.$savename);
					$this->fileList[$field][$k] = array(
							'name'		=> $file,
							'savename'	=> $savename,
							'type'		=> $item['type'][$k],
							'tmp_name'	=> $item['tmp_name'][$k],
							'error'		=> $item['error'][$k],
							'size'		=> $item['size'][$k]
					);
				}
			} else {
				if (UPLOAD_ERR_NO_FILE == $item['error']) {
					continue;
				}
				$this->fileNum++;
				// 文件是否成功上传
				if (UPLOAD_ERR_OK != $item['error']) {
					$this->setUploadErrorMsg($item['error'], $item['name']);
					$this->error = true;
					return false;
				}
				// 文件大小检测
				if (!$this->checkFileSize($field, $item['name'], $item['size'])) {
					return false;
				}
				// 文件格式是否合法
				if (!$this->checkExt($field, $item['name'], $item['tmp_name'])) {
					return false;
				}
				// 文件上传路径验证
				if (!($path=$this->getSavepath($field))) {
					return false;
				}
				$item['savename'] = $this->generateSavename($field).'.'.Fso::getExt($item['name']);
				$saveList[] = array($item['tmp_name'], $path.$item['savename']);
				$this->fileList[$field] = $item;
			}
		}
		
		// 开始将所有上传的文件从临时目录迁移到保存的目录
		foreach ($saveList as $v)
		{
			$dir = dirname($v[1]);
			if (!is_dir($dir)) {
				mkdir($dir, 0775, true);
			}
			move_uploaded_file($v[0], $v[1]);
		}
		
		return true;
	}
	
	/**
	 * 检测上传文件的大小是否超出
	 * @param string $field 上传对应的表单元素名称
	 * @param string $file 上传的文件名称
	 * @param string $size 上传的文件大小
	 */
	public function checkFileSize($field, $file, $size)
	{
		if (is_array($this->maxSize)) {
			// 没设置对应的大小限制，或限制值为0，表示不限制
			if (isset($this->maxSize[$field]) && $this->maxSize[$field] && $this->maxSize[$field] < $size) {
				$this->setFileSizeErrorMsg($file, $this->maxSize[$field]);
				$this->error = true;
				return false;
			} else {
				return true;
			}
		} else if ($this->maxSize && $this->maxSize < $size) {
			$this->setFileSizeErrorMsg($file, $this->maxSize);
			$this->error = true;
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * 获取一个上传字段的文件名
	 * @param string $field
	 */
	public function getSavepath($field)
	{
		if (is_array($this->savePath)) {
			if (isset($this->savePath[$field]) && $this->savePath[$field]) {
				return $this->savePath[$field];
			} else {
				$this->setSavePathErrorMsg();
				$this->error = true;
				return false;
			}
		} else if (!$this->savePath) {
			$this->setSavePathErrorMsg();
			$this->error = true;
			return false;
		} else {
			return $this->savePath;
		}
	}
	
	/**
	 * 获取一个上传字段的文件名
	 * @param string $field
	 */
	public function generateSavename($field)
	{
		if (is_array($this->saveRule)) {
			if (isset($this->saveRule[$field])) {
				return $this->getSavename($this->saveRule[$field]);
			} else {
				return Fso::randFileName();
			}
		} else if ($this->saveRule) {
			return $this->getSavename($this->saveRule);
		} else {
			return Fso::randFileName();
		}
	}
	
	/**
	 * 根据规则获取上传的文件名
	 * @param string $rule
	 * @return string
	 */
	private function getSavename($rule)
	{
		if (!$rule) {
			return Fso::randFileName();
		} else if (0 === strpos($rule, '#')) {
			$params = explode('#', $rule);
			return Fso::randFileName($params[1], $params[2]);
		} else if (function_exists($rule)) {
			return call_user_func($rule);
		} else {
			return $rule;
		}
	}
	
	/**
	 * 检测单个文件的后缀是否符合要求
	 * @param string $field 要检查的表单字段名
	 * @param string $filename 上传的本地文件名
	 * @param string $file 上传到临时目录下的文件路径
	 */
	public function checkExt($field, $filename, $file) 
	{
		$ext = Fso::getExt($filename);
		if (isset($this->allowExts[$field])) {
			if (is_array($this->allowExts[$field]) && !in_array($ext, $this->allowExts[$field])) {
				$this->error = true;
				$this->errorField = $field;
				$this->errorFile = $filename;
				$this->setNotAllowExtsErrorMsg($filename, $this->$this->allowExts[$field]);
				return false;
			} else if ($ext != $this->allowExts[$field]) {
				$this->error = true;
				$this->errorField = $field;
				$this->errorFile = $filename;
				$this->setNotAllowExtsErrorMsg($filename, $this->$this->allowExts[$field]);
				return false;
			}
		} else if (!in_array($ext, $this->allowExts)) {
			$this->error = true;
			$this->errorField = $field;
			$this->errorFile = $filename;
			$this->setNotAllowExtsErrorMsg($filename, $this->allowExts);
			return false;
		} else {
			$ret = Fso::isExtMatchMime($ext, $file);
			if (!$ret) {
				$msg = Core::getLang('upload_file_extension_not_match_mime_type');
				$replacement = array('{file}' => $filename, '{mime-type}'=>Fso::getMimeType($file));
				$this->error(strtr($msg, $replacement));
				$this->error = true;
				$this->errorField = $field;
				$this->errorFile = $filename;
			}
			return $ret;
		}
	}
	
	/**
	 * 设置文件大小超出限制提示
	 * @param string $file
	 * @param int $size
	 */
	private function setFileSizeErrorMsg($file, $size)
	{
		$replacement = array('{file}'=>$file, '{limit}'=>$size);
		$this->error(Core::getLang('upload_file_too_large', $replacement));
	}

	/**
	 * 设置上传路径错误提示
	 * @param string $path
	 */
	private function setSavePathErrorMsg($path='')
	{
		$replacement = array('{path}' => $path);
		$this->error(Core::getLang('upload_path_invalid', $replacement));
		return true;
	}
	
	/**
	 * 设置文件后缀不允许的错误提示
	 * @param string $filename
	 * @param array $exts
	 */
	private function setNotAllowExtsErrorMsg($filename, $exts) 
	{
		$msg = 'upload_file_extension_not_allowed';
		$replacement = array('{file}' => $filename, '{extensions}'=>join(',', $exts));
		$this->error(Core::getLang($msg, $replacement));
		return true;
	}
	
	/**
	 * 设置上传出错的提示
	 * @param int $type
	 * @param string $file
	 */
	private function setUploadErrorMsg($type, $file) 
	{
		if (UPLOAD_ERR_INI_SIZE == $type) {
			$this->setFileSizeErrorMsg($file, ini_get('upload_max_filesize'));
		} else if (UPLOAD_ERR_FORM_SIZE == $type) {
			$this->setFileSizeErrorMsg($file, $_POST['MAX_FILE_SIZE']);
		} else if (UPLOAD_ERR_Cant_WRITE == $type) {
			$this->error(Core::getLang('upload_file_failed_to_write_to_disk', array('{file}' => $file)));
		} else if (UPLOAD_ERR_EXTENSION == $type) {
			$this->error(Core::getLang('upload_file_stopped_by_extension'));
		} else {
			debug_print_backtrace();
			$this->error(Core::getLang('upload_file_stopped_by_extension'));
		}
	}
	
	/**
	 * 返回/设置出错信息
	 */
	public function error($errorMsg=null) 
	{
		if (!is_null($errorMsg)) {
			$this->errorMsg = $errorMsg;
		}
		return $this->errorMsg;
	}
	
}