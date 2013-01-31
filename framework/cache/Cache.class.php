<?php
/**
 * Cache缓存类
 * Created		: 2009-12-21
 * Modified		: 2012-12-22
 * @link		: http://www.dophin.co
 * @copyright	: (C) 2009-2012 Dophin 
 * @version		: 2.0.0
 * @author		: Joseph Chen (chenliq@gmail.com)
 * KEY格式
 * 表TBL_SEP表TBL_FIELD_CONNECTOR字段FIELD_SEP字段CONNECTOR值
 * pre+pre>field,field-key,key
 */
class Cache
{
	/**
	 * 是否已初始化
	 * @var object
	 */
	private static $init = false;
	/**
	 * 缓存类型
	 * @var string
	 */
	public static $cacheType = 'redis';
	/**
	 * 缓存类型对应的类名
	 * @var string
	 */
	public static $cacheClass = 'CacheRedis';
	
	/**
	 * 初始化
	 * @param string $sign
	 */
	public static function init()
	{
		if (!self::$init) {
			$host = $GLOBALS['_cache_host'];
			$port = $GLOBALS['_cache_port'];
			if (isset($GLOBALS['_cache_type']) && $GLOBALS['_cache_type']) {
				self::$cacheType = $GLOBALS['_cache_type'];
				self::$cacheClass = 'Cache'.ucfirst(self::$cacheType);
			}
			if (isset($GLOBALS['_cache_auth'])) {
				$params = array($host, $port, $GLOBALS['_cache_auth']);
			} else {
				$params = array($host, $port);
			}
			call_user_func_array(
						array(self::$cacheClass, 'getInstance'), 
						$params
			);
		}
		return true;
	}
	
	/**
	 * 切换缓存实例
	 * @param string $sign
	 */
	public static function switchInstance($sign) 
	{
		call_user_func_array(
				array(self::$cacheClass, 'getInstance'), 
				array('', '', '', $sign)
		);
	}
	
	/**
	 * 调用当前使用的缓存类
	 * @param string $method
	 * @param array $arguments
	 */
	public static function __callStatic($method, $arguments)
	{
		if (!self::$init) {
			self::init();
		}
		return call_user_func_array(array(self::$cacheClass, $method), $arguments);
	}
	
	/**
	 * 设置缓存类属性
	 * @param string $name
	 * @param mixed $value
	 */
	public static function setProperties($name, $value) 
	{
		ReflectionClassExt::setClassPropertyValue(self::$cacheClass, $name, $value);
	}
}