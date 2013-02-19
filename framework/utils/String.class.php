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
	 * 获取ID基数的路径
	 * @param number $id
	 * @param number $lvl
	 */
	public static function getIdRadixPath($id, $radix=1000, $lvl=3)
	{
		$str = '';
		for ($i=0; $i<$lvl; $i++)
		{
			$str .= floor($id/$radix).'/';
		}
		return $str;
	}
	
	/**
	 * 将一个ID编码
	 * @param int $id
	 */
	public static function idEncode($id)
	{
		$idLen = strlen($id);
		$_len = $idLen;
		$str = $id;
		$minLen = $GLOBALS['_id_encode_minlen'];
		while(true)
		{
			if ($_len>=$minLen) break;
			$str .= substr($GLOBALS['_id_encode_salt_digit'], 0, $minLen-$_len);
			$_len += $idLen;
		}
		$str = UMath::dec2any($str);
		return substr($str, -3).UMath::dec2any($idLen+20).substr($str, 0, -3);
	}
	
	/**
	 * 解码一个字符串为ID值
	 * @param string $str
	 */
	public static function idDecode($str)
	{
		$idLen = UMath::any2dec(substr($str, 3, 1))-20;
		$str = substr($str, 4).substr($str, 0, 3);
		$value = UMath::any2dec($str);
		return substr($value, 0, $idLen);
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
		$salt = strrev($key);
		$char = substr($salt, 0, 1);
		// 强制salt首字符为字母
		if (ctype_digit($char))
		{
			$char += 10;
			$salt = base_convert($char, 10, 36).substr($salt, 1);
		}

		$len = strlen($id);
		$lenChar = UMath::dec2any($len);
		$str = '';
		$keyLen = strlen($key);
		if ($len < $keyLen)
		{
			$id .= substr($salt, 0, $keyLen-$len);
		} else if ($len >= $keyLen) {
			$key .= substr($key, 0, $len-$keyLen+1);
			$keyLen = strlen($key);
			// $id 后一定要有附加串，避免最后一位为0且长度大于key时无法识别
			$id .= $char;
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

		if ($GLOBALS['_id_crypt_slice_pos'])
		{
			$pos = $GLOBALS['_id_crypt_slice_pos'];
			$str = substr($str, -$pos).$lenChar.substr($str, 0, -$pos);
		} else {
			$pos = floor(strlen($str)/2);
			$str = substr($str, 0, $pos).$lenChar.substr($str, $pos);
		}
		
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
		$keyLen = strlen($key);
		$id = '';
		$strLen = strlen($str);
		if ($keyLen < $strLen)
		{
			$key .= substr($key, 0, $strLen-$keyLen+1);
		}
		$ki = 0;
		if ($GLOBALS['_id_crypt_slice_pos'])
		{
			$pos = $GLOBALS['_id_crypt_slice_pos'];
			$idLen = UMath::any2dec(substr($str, $pos, 1));
			$str = substr($str, $pos+1).substr($str, 0, $pos);
		} else {
			$pos = floor((strlen($str)-1)/2);
			$idLen = UMath::any2dec(substr($str, $pos, 1));
			$str = substr($str, 0, $pos).substr($str, $pos+1);
		}
		for ($i=0; $i<$strLen; $i++,$ki++)
		{
			$val = UMath::any2dec($str[$i]);
			$kVal = UMath::any2dec($key[$ki]);
			if ($val < $kVal)
			{
				
				if (isset($str[$i+1]))
				{
					$val = UMath::any2dec($str[$i].$str[$i+1]);
				} else {
					$val = UMath::any2dec($str[$i]);
				}
				$i++;
			}
			$tmp = $val - $kVal;
			if (strlen($id) == $idLen)
			{
				break;
			}
			$id .= $tmp;
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