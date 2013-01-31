<?php
/**
 * 网络相关操作类
 * Created		: 2012-06-21
 * Modified		: 2012-06-28
 * @link		: http://www.dophin.co
 * @copyright	: (C) 2012 Dophin 
 * @version		: 2.0.0
 * @author		: Joseph Chen <chenliq@gmail.com>
 */
class Net
{
	/**
	 * 自动获得标准时间
	 */
	public static function getAtomicTime() 
	{
		$fp = fsockopen('time.nist.gov', 13, $errno, $errstr, 5);
		$str = fread($fp,2096);
		echo($str);
		fclose($fp);
		exit;
	}
}