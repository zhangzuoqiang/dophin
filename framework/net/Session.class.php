<?php
/**
 * Session操作类
 * Created		: 2012-06-19
 * Modified		: 2012-06-19
 * @link		: http://www.dophin.co
 * @copyright	: (C) 2012 Dophin 
 * @version		: 1.0.0
 * @author		: Joseph Chen (chenliq@gmail.com)
 */
class Session
{
	
	public $tbl = 'user_session';
	public $lifeTime = 0;
	
	public function __construct($type='cache') 
	{
		$this->lifeTime = $GLOBALS['_session_lifetime'];
		//不使用 GET/POST 变量方式
		ini_set('session.use_trans_sid', 0);
		// 是否自动启动
		ini_set('session.auto_start', 0);
		//设置垃圾回收最大生存时间
		ini_set('session.gc_maxlifetime', $this->lifeTime);
		//使用 COOKIE 保存 SESSION ID 的方式
		ini_set('session.use_cookies', 1);
		session_set_cookie_params(
			$GLOBALS['_cookie_expire'],
			$GLOBALS['_cookie_path'],
			$GLOBALS['_cookie_domain']
		);
		//将 session.save_handler 设置为 user，而不是默认的 files
		session_module_name('user');
		
		switch ($type)
		{
			case 'memcache':
				ini_set('session.save_handler', $type);
				ini_set('session.save_path', $GLOBALS['_session_path']);
			break;
			
			case 'cache':
				session_set_save_handler(
					 array($this, "cache_open"),
					 array($this, "cache_close"),
					 array($this, "cache_read"),
					 array($this, "cache_write"),
					 array($this, "cache_destroy"),
					 array($this, "cache_gc")
				 );
			break;
			
			// 默认使用db
			default:
				session_set_save_handler(
					 array($this, "db_open"),
					 array($this, "db_close"),
					 array($this, "db_read"),
					 array($this, "db_write"),
					 array($this, "db_destroy"),
					 array($this, "db_gc")
				);
			break;
		}
		
		session_start();
	}
	
	public function cache_open($savePath, $sessionName) 
	{
		return true;
	}
	
	public function cache_close()
	{
		return true;
	}
 
	public function cache_read($id)
	{
		return Cache::get($id);
	}
 
	public function cache_write($id, $data)
	{
		Cache::set($id, $data);
		return true;
	}
 
	public function cache_destroy($id)
	{
		Cache::delete($id);
		return true;
	}
 
	public function cache_gc($maxlifetime) 
	{
		
		return true;
	}
	
	/********** DB操作 **********/
	
	public function db_open($savePath, $sessionName) 
	{
		return true;
	}
	
	public function db_close()
	{
		return true;
	}
 
	public function db_read($id)
	{
		return Db::read($this->tbl, $id, 'sid', 'data');
	}
 
	public function db_write($id, $data)
	{
		$arr = array(
			'sid'			=> $id,
			'data'			=> $data,
			'expire_time'	=> time() + $this->lifeTime
		);
		if (isset($_SESSION['uid'])) {
			$arr['uid'] = $_SESSION['uid'];
		}
		return Db::replace($this->tbl, $arr);
	}
 
	public function db_destroy($id)
	{
		Db::delete($this->tbl, 'sid=:sid', array('sid'=>$id));
		return true;
	}
 
	public function db_gc($maxlifetime) 
	{
		Db::delete($this->tbl, 'expire_time<='.time());
		return true;
	}
	
	/******************** 公共方法 ********************/
	
	/**
	 * 将一个数组序列化成session字符串形式
	 * @return string
	 */
	public function serialize($data) 
	{
		if (!$data) {
			return $data;
		}
		$raw = '' ;
		$line = 0 ;
		$keys = array_keys( $data ) ;
		foreach( $keys as $key ) {
			$value = $data[ $key ] ;
			$line ++ ;
		
			$raw .= $key .'|' ;
		
			if( is_array( $value ) && isset( $value['huge_recursion_blocker_we_hope'] )) {
				$raw .= 'R:'. $value['huge_recursion_blocker_we_hope'] . ';' ;
			} else {
				$raw .= serialize( $value ) ;
			}
			$data[$key] = array( 'huge_recursion_blocker_we_hope' => $line ) ;
		}
		
		return $raw ;
		
	}
	
	/**
	 * 将session字符串反序列化成一个数组
	 * @param string $data
	 * @return array
	 */
	public function unserialize($data) 
	{
		if (strlen($data) == 0) {
			return array();
		}
		
		// match all the session keys and offsets
		preg_match_all('/(^|;|\})([a-zA-Z0-9_]+)\|/i', $data, $matchesarray, PREG_OFFSET_CAPTURE);
		
		$returnArray = array();
		
		$lastOffset = null;
		$currentKey = '';
		foreach ( $matchesarray[2] as $value )
		{
			$offset = $value[1];
			if(!is_null( $lastOffset))
			{
				$valueText = substr($data, $lastOffset, $offset - $lastOffset );
				$returnArray[$currentKey] = unserialize($valueText);
			}
			$currentKey = $value[0];
		
			$lastOffset = $offset + strlen( $currentKey )+1;
		}
		
		$valueText = substr($data, $lastOffset );
		$returnArray[$currentKey] = unserialize($valueText);
		
		return $returnArray;
		
	}
	
}