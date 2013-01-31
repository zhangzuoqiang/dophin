<?php
/**
 * IP操作类
 * Created		: 2007-10-10
 * Modified		: 2012-06-28
 * @link		: http://www.dophin.co
 * @copyright	: (C) 2007-2012 Dophin 
 * @version		: 2.0.0
 * @author		: Joseph Chen <chenliq@gmail.com>
 */
class Ip
{
	/**
	 * 错误提示信息
	 * @var string
	 */
	public static $msg = '';

	/**
	 * 获取客户端IP
	 * @return String
	 */
	public static function getIp()
	{
		if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
			$ip = getenv('HTTP_CLIENT_IP');
		} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
			$ip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
			$ip = getenv('REMOTE_ADDR');
		} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
			$ip = $_SERVER['REMOTE_ADDR'];
		} else {
			$ip = '0.0.0.0';
		}
		$ip = (false !== ip2long($ip)) ? $ip : '0.0.0.0';
		return $ip;
	}

	/**
	 * 将标准的IP形式转化成十进制形式IP
	 * @param String $ip
	 * @return Interger
	 */
	public static function ip2long($ip='')
	{
		empty($ip) && $ip = self::getIp();
		//返回浮点型,不能转换成整型,否则会溢出
		//php支持的最大整型为2147483647
		return (float)sprintf('%u',ip2long($ip));
	}

	/**
	 * 将十进制形式IP转化成标准的IP形式
	 *
	 * @param Integer $ip
	 * @return String
	 */
	public static function long2ip($ip)
	{
		return long2ip($ip);
	}

	/**
	 * 获取转换成整型的IP地址
	 * @param String $ip
	 * @return float
	 */
	public static function getIntIp($ip='')
	{
		return self::ip2long($ip);
	}
	
	/**
	 * 获取IP地址对应的地理位置
	 * @param string $ip
	 */
	public static function getIpLocation($ip=NULL, $charset='UTF-8')
	{
		if (!$ip) {
			$ip = self::getIp();
		}
		//IP数据文件路径
		if (!is_file($dat_path=CORE_PATH.'data/qqwry.dat')) {
			$dat_path = dirname(__FILE__).DS.'qqwry.dat';	//检查IP地址
		}
		if(!preg_match("/^\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3}$/", $ip)){
			return 'IP 地址错误！';
		}
		//打开IP数据文件
		if(!$fp = @fopen($dat_path, 'rb')) {
			return 'IP数据文件无法读取，请确保是正确的纯真IP库！';
		} 
		//分解IP进行运算，得出整形数
		$ip = explode('.', $ip);
		//获取IP数据索引开始和结束位置
		$ipNum = $ip[0] * 16777216 + $ip[1] * 65536 + $ip[2] * 256 + $ip[3];
		$DataBegin = fread($fp, 4);
		$DataEnd = fread($fp, 4);
		if (!strlen($DataBegin) || !strlen($DataEnd)) {
			return 'Unknown Error';
		}
		//unpack() 函数从二进制字符串对数据进行解包。unpack(format,data) L - unsigned long (always 32 bit, machine byte order)
		$ipbegin = implode('', unpack('L', $DataBegin));
		//$ipbegin 值如：5386001
		if($ipbegin < 0) $ipbegin += pow(2, 32);
		$ipend = implode('', unpack('L', $DataEnd));
		if($ipend < 0) $ipend += pow(2, 32);
		$ipAllNum = ($ipend - $ipbegin) / 7 + 1;
	   
		$BeginNum = 0;
		$EndNum = $ipAllNum;	//使用二分查找法从索引记录中搜索匹配的IP记录
		$ip1num=''; $ip2num='';   $ipAddr1='';	$ipAddr2='';
		while($ip1num>$ipNum || $ip2num<$ipNum)
		{
			$Middle= intval(($EndNum + $BeginNum) / 2);		//偏移指针到索引位置读取4个字节
			fseek($fp, $ipbegin + 7 * $Middle);
			$ipData1 = fread($fp, 4);
			if(strlen($ipData1) < 4) {
				fclose($fp);
				return 'System Error';
			}
			//提取出来的数据转换成长整形，如果数据是负数则加上2的32次幂
			$ip1num = implode('', unpack('L', $ipData1));
			if($ip1num < 0) $ip1num += pow(2, 32);
		   
			//提取的长整型数大于我们IP地址则修改结束位置进行下一次循环
			if($ip1num > $ipNum) {
				$EndNum = $Middle;
				continue;
			}
		   
			//取完上一个索引后取下一个索引
			$DataSeek = fread($fp, 3);
			if(strlen($DataSeek) < 3) {
				fclose($fp);
				return 'System Error';
			}
			$DataSeek = implode('', unpack('L', $DataSeek.chr(0)));
			fseek($fp, $DataSeek);
			$ipData2 = fread($fp, 4);
			if(strlen($ipData2) < 4) {
				fclose($fp);
				return 'System Error';
			}
			$ip2num = implode('', unpack('L', $ipData2));
			//没找到提示未知
			if($ip2num < 0) $ip2num += pow(2, 32);
			if($ip2num < $ipNum) {
				if($Middle == $BeginNum) {
					fclose($fp);
					return 'Unknown';
				}
				$BeginNum = $Middle;
			}
		}
		$ipFlag = fread($fp, 1);
		if($ipFlag == chr(1)) {
			$ipSeek = fread($fp, 3);
			if(strlen($ipSeek) < 3) {
				fclose($fp);
				return 'System Error';
			}
			$ipSeek = implode('', unpack('L', $ipSeek.chr(0)));
			fseek($fp, $ipSeek);
			$ipFlag = fread($fp, 1);
		}
		if($ipFlag == chr(2)) {
			$AddrSeek = fread($fp, 3);
			if(strlen($AddrSeek) < 3) {
				fclose($fp);
				return 'System Error';
			}
			$ipFlag = fread($fp, 1);
			if($ipFlag == chr(2)) {
				$AddrSeek2 = fread($fp, 3);
				if(strlen($AddrSeek2) < 3) {
					fclose($fp);
					return 'System Error';
				}
				$AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0)));
				fseek($fp, $AddrSeek2);
			} else {
				fseek($fp, -1, SEEK_CUR);
			}		while(($char = fread($fp, 1)) != chr(0))
				$ipAddr2 .= $char;		$AddrSeek = implode('', unpack('L', $AddrSeek.chr(0)));
			fseek($fp, $AddrSeek);		while(($char = fread($fp, 1)) != chr(0))
				$ipAddr1 .= $char;
		} else {
			fseek($fp, -1, SEEK_CUR);
			while(($char = fread($fp, 1)) != chr(0))
				$ipAddr1 .= $char;		$ipFlag = fread($fp, 1);
			if($ipFlag == chr(2)) {
				$AddrSeek2 = fread($fp, 3);
				if(strlen($AddrSeek2) < 3) {
					fclose($fp);
					return 'System Error';
				}
				$AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0)));
				fseek($fp, $AddrSeek2);
			} else {
				fseek($fp, -1, SEEK_CUR);
			}
			while(($char = fread($fp, 1)) != chr(0)){
				$ipAddr2 .= $char;
			}
		}
		fclose($fp);	//最后做相应的替换操作后返回结果
		if(preg_match('/http/i', $ipAddr2)) {
			$ipAddr2 = '';
		}
		$ipaddr = "$ipAddr1 $ipAddr2";
		$ipaddr = preg_replace('/CZ88.Net/is', '', $ipaddr);
		$ipaddr = preg_replace('/^s*/is', '', $ipaddr);
		$ipaddr = preg_replace('/s*$/is', '', $ipaddr);
		if(preg_match('/http/i', $ipaddr) || $ipaddr == '') {
			$ipaddr = 'Unknown';
		}
		if (strtoupper($charset) == 'GBK') {
			return $ipaddr;
		} else {
			return iconv('GBK', $charset, $ipaddr);
		}
	}
	
	/**
	 * 设置IP地址文件路径
	 */
	public static function setIpDatFile($file) 
	{
		self::$ipDatFile = $file;
	}
	
}


