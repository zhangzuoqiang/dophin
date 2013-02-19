<?php
/**
 * 用户操作类
 * Created		: 2012-05-28
 * Modified		: 2012-06-19
 * @version		: 1.0.0
 * @author		: Joseph Chen (chenliq@gmail.com)
 */
class User 
{
	/**
	 * 是否已登录
	 * @var boolean
	 */
	private static $isLogined = false;
	/**
	 * 用户表
	 * @var string
	 */
	public static $tbl = 'user';
	/**
	 * 用户登录表
	 * @var string
	 */
	public static $tbl_login = 'user_login';
	/**
	 * 用户在线表
	 * @var string
	 */
	public static $tbl_online = 'user_online';
	/**
	 * 错误提示信息
	 * @var string
	 */
	public static $msg = '';
	/**
	 * 当前登录用户的数据
	 * @var array
	 */
	public static $data = array();
	
	/**
	 * 初始化用户数据
	 */
	public static function init()
	{
		// 不初始化session，则使用cookie方式保存用户在线信息
		if (!isset($_SESSION))
		{
			if (empty($_COOKIE['uid']) || empty($_COOKIE['sid']))
			{
				self::$isLogined = false;
				self::$data = array();
			} else {
				self::verifyCookie();
			}
		} else if (empty($_SESSION['uid']) || empty($_SESSION['username'])) {
			self::$isLogined = false;
			self::$data = array();
		} else {
			self::$isLogined = true;
			self::$data = self::read($_SESSION['uid']);
		}
	}
	
	/**
	 * 判断当前用户是否已登录
	 * @param string|int $username
	 * @return boolean
	 */
	public static function isLogined($username=null, $isUid=false) 
	{
		// 默认取当前用户
		if (is_null($username)) {
			return self::$isLogined;
		}
		else if ($isUid) {
			if ($GLOBALS['_cache_enable'])
			{
				return Cache::get('uol:'.$username);
			} else {
				return Db::read(self::$tbl_online, $username, 'uid');
			}
		} else {
			if ($GLOBALS['_cache_enable'])
			{
				return Cache::get('uol:'.self::getUidByUsername($username));
			} else {
				return Db::read(self::$tbl_online, $username, 'username');
			}
		}
	}
	
	/**
	 * 根据用户名获取用户ID
	 * @param string $username
	 * @return int
	 */
	public static function getUidByUsername($username) 
	{
		if ($GLOBALS['_cache_enable'])
		{
			$uid = Cache::get('uid'.$username);
			if (!$uid) {
				$uid = Db::read(self::$tbl, $value, 'username', 'uid');
				if ($uid) {
					// 数据缓存10天
					Cache::set('uid'.$username, $uid, 864000);
				}
			}
		} else {
			$uid = Db::read(self::$tbl, $value, 'username', 'uid');
		}
		return (int)$uid;
	}
	
	/**
	 * 读取用户信息
	 * @param string $val
	 * @param string $field
	 */
	public static function read($val, $fetchFields='*', $field='uid', $useCache=true) 
	{
		if ($GLOBALS['_cache_enable'] && $useCache) {
			if ($field != 'uid') {
				$val = self::getUidByUsername($val);
			}
			$key = 'u:'.$val;
			if ($fetchFields=='' || $fetchFields=='*') {
				$fetchFields = null;
			} else if (is_string($fetchFields)) {
				$fetchFields = explode(',', $fetchFields);
			}
			$data = Cache::getHash($key, $fetchFields);
			if (!$data) {
				$data = Db::read(self::$tbl, $val, $field);
				Cache::setHash($key, $data, $GLOBALS['_cache_user_expire']);
			}
		} else {
			$data = Db::read(self::$tbl, $val, $field, $fetchFields);
		}
		return $data;
	}

	/**
	 * 用户账号密码验证
	 * @param string $user
	 * @param string $pass
	 * @param boolean $cacheData 验证成功是否缓存数据
	 */
	public static function verify($username, $pass, $cacheData=true)
	{
		$user = Db::read(self::$tbl_login, $username, 'username');
		// 用户名不存在
		if (!$user) {
			self::$msg = Core::getLang('username_not_exist');
			return false;
		} else if (self::hashPassword($pass, $user['salt']) == $user['password']) {
			self::$msg = Core::getLang('login_success');
			$_SESSION['uid']		= $user['uid'];
			$_SESSION['username']	= $username;
			if ($cacheData && $GLOBALS['_cache_enable']) {
				$user = self::read($user['uid']);
				Cache::setHash('u'.$user['uid'], $user, $GLOBALS['_cache_user_expire']);
			}
			return true;
		} else { //密码错误
			self::$msg = Core::getLang('password_incorrect');
			return false;
		}
	}
	
	/**
	 * 加密密码
	 * @param string $pass
	 * @param string $salt
	 * @return string
	 */
	public static function hashPassword($password, $salt)
	{
		return md5(md5(md5($password).md5($salt)));
	}
	
	/**
	 * 添加用户的部分字段值
	 * @param int $uid
	 * @param int $val 增加的值
	 * @param int $cid 操作分类ID
	 * @param string $field 增加值的字段
	 * @param string $orderNo 订单号
	 * @param string $content 内容
	 */
	public static function addVal($uid, $val, $cid, $field='gold', $orderNo='', $content='') 
	{
		// 先判断操作字段是否在可允许列表
		$allowField = array('gold', 'gold_bond', 'coin', 'credit');
		if (!in_array($filed, $allowField)) {
			self::$msg = Core::getLang('user_add_val_field_unallowed');
			return false;
		}
		$uid = (int)$uid;
		$user = self::read($uid, 'username,'.$field);
		// 判断字段值是否足够
		if ($user[$field] < $val) {
			self::$msg = Core::getLang($field.'_value_not_enough');
			return false;
		}
		// 进行用户数据字段更新
		if ($ret) {
			$data = array(
				$field	=> $val
			);
			$ret = Db::update(self::$tbl, $data, 'uid='.$uid);
		}
		if ($ret) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 加密生成Cookie中sid
	 * @param array $user
	 */
	private static function hashSid($user) 
	{
		return md5($user['uid'].$user['last_login_ip'].$user['last_login_time'].$user['last_online_time']);
	}

	/**
	 * 验证cookie中用户信息是否合法
	 */
	private static function verifyCookie()
	{
		if (!ctype_digit($_COOKIE['uid']))
		{
			self::$isLogined = false;
			self::$data = array();
			return false;
		}
		$user = Db::read($_COOKIE['uid'], self::$tbl_online, 'uid');
		if (!$user)
		{
			self::$isLogined = false;
			self::$data = array();
			return false;
		}
		
		if (self::hashSid($user) == $_COOKIE['sid'])
		{
			self::$isLogined = true;
			self::$data = self::read($_COOKIE['uid']);
			return true;
		} else {
			self::$isLogined = false;
			self::$data = array();
			return false;
		}
	}
}