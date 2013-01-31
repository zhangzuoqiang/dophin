<?php
class UMath
{
	/**
	 * 进制基字符串
	 * @var string
	 */
	public static $radixStr = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_=,!+';
	
	
	/**
	 * 10进制转成任意进制
	 * @param float $num
	 * @param int $base
	 * @return string
	 */
	public static function dec2any($num, $base=62)
	{
		if ($base==62)
		{
			$index = self::$radixStr;
		} else {
			$index = substr(self::$radixStr, 0, $base);
			$base = strlen($index);
		}
		$out = '';
		for ($i=floor(log10($num)/log10($base)); $i>=0; $i--)
		{
			$a = floor( $num / pow( $base, $i ) );
			$out = $out . substr( $index, $a, 1 );
			$num = $num - ( $a * pow( $base, $i ) );
		}
		return $out;
	}

	/**
	 * 任意进制转成10进制
	 * @param string $str
	 * @param int $base
	 * @return float
	 */
	public static function any2dec($str, $base=62)
	{
		if ($base==62)
		{
			$index = self::$radixStr;
		} else {
			$index = substr(self::$radixStr, 0, $base);
			$base = strlen($index);
		}
		$out = 0;
		$len = strlen($str) - 1;
		for ($i=0; $i<=$len; $i++)
		{
			$out = $out + strpos($index, substr($str, $i, 1)) * pow($base, $len - $i);
		}
		return $out;
	}

}