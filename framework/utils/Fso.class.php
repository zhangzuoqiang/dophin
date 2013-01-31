<?php
/**
 * 文件系统操作
 *
 * Created		: 2012-06-21
 * Modified		: 2012-06-21
 * @link		: http://www.dophin.co
 * @copyright	: (C) 2012 Dophin 
 * @version		: 0.1.0
 * @author		: Joseph Chen (chenliq@gmail.com)
 */
class Fso
{
	
	/**
	 * 获取文件名对应的后缀
	 * @param string $file
	 */
	public static function getExt($file)
	{
		return strtolower(pathinfo($file,PATHINFO_EXTENSION));
	}
	
	/**
	 * 判断文件名后缀和类型是否一致
	 * @param string $ext
	 * @param string $file
	 */
	public static function isExtMatchMime($ext, $file) 
	{
		return (self::$extMimeMapping[$ext] == self::getMimeType($file));
	}
	
	/**
	 * 获取文件的mime-type
	 * @param string $file
	 */
	public static function getMimeType($file)
	{
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$type = finfo_file($finfo, $file);
		finfo_close($finfo);
		return $type;
	}
	
	/**
	 * 随机生成一个文件名
	 * @param string $function
	 * @param string $date
	 * @return string
	 */
	public static function randFileName($function='', $date='Ym/') 
	{
		switch ($function)
		{
			case 'time':
				$name = time();
			break;
			
			case 'microtime':
				$name = strtr(microtime(1),array('.'=>''));
			break;
			
			case 'uniqid':
				$name = uniqid();
			break;
			
			case 'microuniqid':
				$name = substr(microtime(1),-4).uniqid();
			break;
			
			case 'uniqidmicro':
				$name = uniqid().substr(microtime(1),-4);
			break;
			
			default:
				$name = substr(microtime(1),-4).uniqid();
			break;
		}
		return date($date).$name.rand(1, 999);
	}
	
	public static $extMimeMapping = array(
		'gif'	=> 'image/gif',
		'jpg'	=> 'image/jpeg',
		'png'	=> 'image/png',
		'rar'	=> 'application/x-rar',
		'zip'	=> 'application/zip',
	);
	
}