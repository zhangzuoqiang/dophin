<?php
/**
 * Redis操作类
 * Created		: 2012-06-17
 * Modified		: 2012-06-17
 * @link		: http://www.dophin.co
 * @copyright	: (C) 2009-2012 Dophin 
 * @version		: 1.0.0
 * @author		: Joseph Chen (chenliq@gmail.com)
 */
class CacheRedis
{
	/**
	 * 表分隔符
	 */
	const SEP = '-';
	
	/**
	 * 当前实例化对象
	 * @var object
	 */
	public static $instance = null;
	/**
	 * 已实例化对象的列表
	 * @var array
	 */
	public static $instanceList = array();

	/**
	 * 获取Redis实例
	 * @param string $host
	 * @param string $port
	 * @param string $auth
	 * @param string $sign
	 */
	public static function getInstance($host='localhost', $port='6379', $auth='', $sign='default')
	{
		if (!self::$instance) {
			self::$instance = new Redis();
			self::$instance->connect($host, $port);
			if ($auth) {
				self::$instance->auth($auth);
			}
			self::$instanceList[$sign] = self::$instance;
		} else if (isset(self::$instanceList[$sign])) {
			self::$instance = self::$instanceList[$sign];
		}
		return self::$instance;
	}
	
	/**
	 * 清空缓存
	 * @param int $dbid 要清空的数据库(默认全部)
	 */
	public static function clear($dbid=null) 
	{
		if (is_null($dbid)) {
			self::$instance->flushAll();
		} else {
			self::$instance->select($dbid);
			self::$instance->flushDB();
		}
	}
	
	/**
	 * 设置一个key的值
	 * @param string $key
	 * @param string $value
	 * @param int $expire
	 */
	public static function set($key, $value, $expire=0)
	{
		if (ctype_digit((string)$expire) && $expire) {
			return self::$instance->setex($key, $expire, $value);
		} else {
			return self::$instance->set($key, $value);
		}
	}
	
	/**
	 * 设置一个hash数组
	 * @param string $key
	 * @param array $hash
	 * @param int $expire
	 */
	public static function setHash($key, $hash, $expire=0) 
	{
		self::$instance->hmset($key, $hash);
		if (ctype_digit((string)$expire) && $expire) {
			self::$instance->expire($key, $expire);
		}
		return true;
	}
	
	/**
	 * 获取hash数据
	 * @param string $key
	 * @param array|null $fields
	 */
	public static function getHash($key, $fields=null) 
	{
		if (!is_array($fields)) {
			return self::$instance->hgetall($key);
		} else {
			return self::$instance->hmget($key, $fields);
		}
	}
	
	/**
	 * 取得有序集中第一条记录
	 * @param string $key
	 * @param boolean $withscores 是否同时返回值
	 */
	public static function zfirst($key, $withscores=true)
	{
		return self::$instance->zRange($key, 0, 0, $withscores);
	}
	
	/**
	 * 取得有序集中最后一条记录
	 * @param string $key
	 * @param boolean $withscores 是否同时返回值
	 */
	public static function zlast($key, $withscores=true)
	{
		return self::$instance->zRevRange($key, 0, 0, $withscores);
	}

	/**
	 * 取得有序集中所有记录
	 * @param string $key
	 * @param boolean $withscores 是否同时返回值
	 */
	public static function zgetall($key, $withscores=true)
	{
		return self::$instance->zRange($key, 0, -1, $withscores);
	}

	/**
	 * 根据表前缀设置缓存数据
	 * @param string $key
	 * @param string $pre
	 */
	public static function sets($key, $value, $pre='', $expire=0) 
	{
		$key = $pre.$key;
		self::set($key, $value, $expire);
	}

	/**
     * 根据表前缀获取缓存数据(如果多个key,则获取所有key对应的值)
     * @param string|array $key
	 * @param string $pre
	 */
	public static function gets($key, $pre='') 
	{
    	if (is_array($key)) {
    		$arr = array();
    		foreach ($key as $k) {
    			if ($pre) {
    				$arr[] = $pre.$k;
    			} else {
    				$arr[] = $k;
    			}
    		}
    		return self::$instance->getMultiple($arr);
    	} else {
    		$pre && $key = $pre.$key;
	    	if (!self::$instance->exists($key)) {
	    		return false;
	    	}
	    	return self::$instance->get($key);
    	}
	}
	
	/**
	 * 调用当前使用的缓存类
	 * @param string $method
	 * @param array $arguments
	 */
	public static function __callStatic($method, $arguments)
	{
		if (!self::$instance) {
			self::getInstance($GLOBALS['_cache_host'], $GLOBALS['_cache_port'], $GLOBALS['_cache_auth']);
		}
		return call_user_func_array(array(self::$instance, $method), $arguments);
	}
}