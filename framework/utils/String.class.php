<?php
/**
 * 字符串操作
 *
 * Created		: 2012-06-21
 * Modified		: 2012-06-21
 * @link		: http://www.dophin.co
 * @copyright	: (C) 2012 Dophin 
 * @version		: 0.1.0
 * @author		: Joseph Chen (chenliq@gmail.com)
 */
class String
{
	const RAND_INT		= 1;
	const RAND_LOWER	= 2;
	const RAND_UPPER	= 4;
	const RAND_SYMBOL	= 8;
	
	/**
	 * 用于ID加密密钥
	 * @var string
	 */
	public static $_id_crypt_key = '';
	
	/**
	 * 判断变量是否纯数字ID的组合字符串(数字字符串用","分隔)
	 * @param int | string $s
	 * @return Boolean
	 */
	public static function digitSepBunch($s)
	{
		return (preg_match('/^(?:[\d]+\,{0,1})+[\d]+$/', $s));
	}
	
	/**
	 * 随机一个字符串
	 * @param int $len
	 * @param int $type
	 * @param string $exceptChars 需要排除的字符
	 */
	public static function rand($len=8, $type=7, $exceptChars='01iIlLoO')
	{
		$numeric = '0123456789';
		$lower	= 'abcdefghijklmnopqrstuvwxyz';
		$upper	= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$symbol  = '`~!@#$%^&*()-_=+[{]}/\\|;:\'",<.>/?';
		$ret = $str = '';
		($type & self::RAND_INT) && $str .= $numeric;
		($type & self::RAND_LOWER) && $str .= $lower;
		($type & self::RAND_UPPER) && $str .= $upper;
		($type & self::RAND_SYMBOL) && $str .= $symbol;
		for($i=0; $i<$len; $i++)
		{
			$rnd = mt_rand(0, strlen($str)-1);
			$char = $str[$rnd];
			if (false !== strpos($exceptChars, $char))
			{
				$i--;
				continue;
			}
			$ret .= $char;
		}
		return $ret;
	}

	/**
	 * 将一个ID编码
	 * @param int $id
	 */
	public static function idEncode($id)
	{
		$id = (string)$id;
		$len = strlen($id);
		$len36 = base_convert($len, 10, 36);
		$str = $len36;
		if ($len > 6)
		{
			$start = 1;
			while(true)
			{
				$digit = substr($id, $start-1, 3);
				$rnd = mt_rand(1064, 3097);
				$str .= UMath::dec2any($rnd).UMath::dec2any($rnd - (int)$digit);
	
				$start += 3;
				if ($start >= $len)
				{
					break;
				}
			}
		} else if ($len > 4) {
			$start = 1;
			while(true)
			{
				$digit = substr($id, $start-1, 3);
				$rndLen = rand(1, 4);
				$rnd = mt_rand(pow(64, $rndLen-1)+1, pow(64, $rndLen));
				$tmp = UMath::dec2any($rnd);
				$value = $digit + $rnd;
				$total = UMath::dec2any($value);
				$str .= $rndLen.$tmp.strlen($total).$total;
	
				$start += 3;
				if ($start >= $len)
				{
					break;
				}
			}
		} else {
			$rnd = mt_rand(110, 30592);
			$value = $id * $rnd;
			$tmp = UMath::dec2any($rnd);
			$str .= strlen($tmp).$tmp.UMath::dec2any($value);
		}
		return $str;
	}
	
	/**
	 * 解码一个字符串为ID值
	 * @param string $str
	 */
	public static function idDecode($str)
	{
		$id = '';
		$len = strlen($str);
		$idLen = base_convert(substr($str, 0, 1), 36, 10);
		
		if ($idLen > 4)
		{
			$pos = 1;
			while(true)
			{
				$rnd = substr($str, $pos, 2);
				$pos += 2;
				$value = substr($str, $pos, 2);
				$pos += 2;
				$value = UMath::dec2any($rnd) - UMath::dec2any($value);
				$id .= $value;
				if ($pos >= $len)
				{
					break;
				}
			}
		} else if ($idLen > 4) {
			$pos = 1;
			while(true)
			{
				$rndLen = substr($str, $pos, 1);
				$pos++;
				$rnd = UMath::dec2any(substr($str, $pos, $rndLen));
				$pos += $rndLen;
				$totalLen = substr($str, $pos, 1);
				$pos++;
				$total = UMath::dec2any(substr($str, $pos, $totalLen));
				$value = $total - $rnd;
				$id .= $value;
				$pos += $totalLen;
				if ($pos >= $len)
				{
					break;
				}
			}
		} else {
			$rndLen = substr($str, 1, 1);
			$rnd = UMath::dec2any(substr($str, 2, $rndLen));
			$value = UMath::dec2any(substr($str, 2+$rndLen));
			$id = $value / $rnd;
		}
		return $id;
	}

	/**
	 * 将一个ID加密并base64编码
	 * @param int $id
	 * @return string $str
	 */
	public static function idEncode64($id)
	{
		$key = self::idCryptKey();
		$salt = strrev($key);
		$id = (string)$id;
		$str = '';
		$pos = 0;
		$len = strlen($id);
		$keyLen = strlen($key);
		if ($len < $keyLen)
		{
			$id .= '|'.substr($salt, 0, $keyLen-$len);
			$len = strlen($id);
		}
		for ($i=0; $i<$len; $i++)
		{
			if (isset($key[$i]))
			{
				$char = $key[$i];
				$pos++;
			} else {
				$pos = 0;
				$char = $key[0];
			}
			$c = $id[$i] ^ $char;
			$str .= $c;
		}
		$str = base64_encode($str);
		return $str;
	}
	
	/**
	 * 解密并base64编码一个字符串为ID值
	 * @param string $str
	 * @return int $id
	 */
	public static function idDecode64($str)
	{
		$key = self::idCryptKey();
		$id = '';
		$pos = 0;
		$str = base64_decode($str);
		$len = strlen($str);
		for ($i=0; $i<$len; $i++)
		{
			if (isset($key[$i]))
			{
				$char = $key[$i];
				$pos++;
			} else {
				$pos = 0;
				$char = $key[0];
			}
			$c = $str[$i] ^ $char;
			if ($c == '|')
			{
				break;
			}
			$id .= $c;
		}
		return $id;
	}
	
	/**
	 * 加密ID数字为一个字符串
	 * @param int $id
	 * @return string
	 */
	public static function idCrypt($id)
	{
		$key = self::idCryptKey();
// 		$salt = str_shuffle($key);
		$salt = strrev($key);
		$char = substr($salt, 0, 1);
		// 强制salt首字符为字母
		if (ctype_digit($char))
		{
			$char += 10;
			$salt = base_convert($char, 10, 36).substr($salt, 1);
		}

		$len = strlen($id);
		$str = '';
		$keyLen = strlen($key);
		if ($len < $keyLen)
		{
			$id .= substr($salt, 0, $keyLen-$len);
		}
		for ($i=0; $i<$keyLen; $i++)
		{
			if ($i <= $len)
			{
				$val = $id[$i];
			} else {
				$val = UMath::any2dec($id[$i]);
			}
			
			$sum = $val + UMath::any2dec($key[$i]);
			$tmp = UMath::dec2any($sum);
			$str .= $tmp;
		}
		
		$str = substr($str, -6).substr($str, 0, -6);
		
		return $str;
	}

	/**
	 * 解密一个字符串为ID数字
	 * @param string $str
	 * @return string
	 */
	public static function idDeCrypt($str)
	{
		$key = self::idCryptKey();
		$id = '';
		$strLen = strlen($str);
		$ki = 0;
		$str = substr($str, 6).substr($str, 0, 6);
		for ($i=0; $i<$strLen; $i++,$ki++)
		{
			$val = UMath::any2dec($str[$i]);
			$kVal = UMath::any2dec($key[$ki]);
			if ($val < $kVal)
			{
				$val = UMath::any2dec($str[$i].$str[$i+1]);
				$i++;
			}
			$tmp = $val - $kVal;
			if ($tmp > 9)
			{
				// 值大于9，说明是附加串了，不需要再获取了，跳出循环
				break;
			} else {
				$id .= $tmp;
			}
		}

		return $id;
	}
	
	/**
	 * 设置/获取ID加密密钥
	 * @param string $key
	 * @return string
	 */
	public static function idCryptKey($key='') 
	{
		if (!$key)
		{
			if (self::$_id_crypt_key)
			{
				return self::$_id_crypt_key;
			} else {
				self::$_id_crypt_key = $GLOBALS['_id_crypt_key'];
			}
		} else {
			self::$_id_crypt_key = $key;
		}
		return self::$_id_crypt_key;
	}
	
}