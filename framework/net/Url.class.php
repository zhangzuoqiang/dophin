<?php
/**
 * URL操作
 *
 * Created		: 2012-05-25
 * Modified		: 2012-07-07
 * @link		: http://www.dophin.co
 * @copyright	: (C) 2012 Dophin 
 * @version		: 0.2.0
 * @author		: Joseph Chen (chenliq@gmail.com)
 */
class Url
{
	public static $urlPattern = '~(https?://[\w\./\?\#\=\&\-\%\;]+)~';
	
	/**
	 * 短网址生成
	 * @param string $url
	 */
	public static function shorturl($url)
	{
		$url = crc32($url);
		$result = sprintf("%u", $url);
		return self::code62($result);
	}
	
	/**
	 * 短网址生成编码
	 * @param string $x
	 */
	public static function code62($x)
	{
		$show = '';
		while($x > 0) {
			$s = $x % 62;
			if ($s > 35) {
				$s = chr($s+61);
			} elseif ($s > 9 && $s <=35) {
				$s = chr($s + 55);
			}
			$show .= $s;
			$x = floor($x/62);
		}
		return $show;
	}
	
	/**
	 * 调用google的api生成短网址
	 * @param string $long_url
	 */
	public static function shortenGoogleUrl($long_url)
	{
		$apiKey = 'AIzaSyA9nILUpH0bW0sy733CkslaWkP7xYT7q98';
		$postData = array('longUrl' => $long_url, 'key' => $apiKey);
		$jsonData = json_encode($postData);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
		$response = curl_exec($curl);
		curl_close($curl);
		$json = json_decode($response);
		return $json->id;
	}
	
	/**
	 * 调用google的api从短网址还原成原网址
	 * @param string $short_url
	 */
	public static function expandGoogleUrl($short_url)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url?shortUrl='.$short_url);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		$response = curl_exec($curl);
		curl_close($curl);
		$json = json_decode($response);
		var_export($json);
		return $json->longUrl;
	}

	/**
	 * CURL方式获取网页内容
	 * @param string $url
	 * @param int $timeout
	 * @return string
	 */
	public static function getContents($url, $timeout=10)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		// 修正301/302跳转取不到网页内容的问题
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$content = curl_exec($ch);
		curl_close($ch);
		return $content;
	}
	
	/**
	 * 将一组数据POST提交到一个URL
	 * @param string $url
	 * @param int $timeout
	 */
	public static function post($url, $data, $timeout=10) 
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$content = curl_exec($ch);
		curl_close($ch);
		return $content;
	}
	
	/**
	 * 判断字符串中是否包含有URL链接
	 * @param string $str
	 */
	public static function containUrl($str)
	{
		return preg_match(self::$urlPattern, $str);
	}
	
	/**
	 * 自动给URL加上链接
	 * @param string $str
	 */
	public static function addLink($str)
	{
		return preg_replace(self::$urlPattern, '<a href="$1" target="_blank">$1</a>', $str);
	}
}