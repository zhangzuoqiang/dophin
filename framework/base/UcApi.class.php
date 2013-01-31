<?php
/**
 * UCENTER接口
 *
 * Created		: 2012-01-14
 * Modified		: 2012-01-14
 * @link		: http://www.mp920.com
 * @copyright	: (C) 2012 Dophin 
 * @version		: 0.0.1
 * @author		: Joseph Chen (chenliq@gmail.com)
 */
define('API_DELETEUSER', 1);
define('API_RENAMEUSER', 1);
define('API_GETTAG', 1);
define('API_SYNLOGIN', 1);
define('API_SYNLOGOUT', 1);
define('API_UPDATEPW', 1);
define('API_UPDATEBADWORDS', 1);
define('API_UPDATEHOSTS', 1);
define('API_UPDATEAPPS', 1);
define('API_UPDATECLIENT', 1);
define('API_UPDATECREDIT', 1);
define('API_GETCREDITSETTINGS', 1);
define('API_GETCREDIT', 1);
define('API_UPDATECREDITSETTINGS', 1);

define('API_RETURN_SUCCEED', '1');
define('API_RETURN_FAILED', '-1');
define('API_RETURN_FORBIDDEN', '-2');

class UcApi
{

	/**
	 * 接口测试是否连通
	 */
	public static function test()
	{
		return API_RETURN_SUCCEED;
	}
	
	/**
	 * 同步登录
	 * @param array $get
	 * @param array $post
	 */
	public static function synlogin($get, $post)
	{
		Db::init(
			array(
				'host'		=> UC_DBHOST,
				'name'		=> UC_DBNAME,
				'user'		=> UC_DBUSER,
				'pass'		=> UC_DBPW,
				'charset'	=> UC_DBCHARSET,
				'persistent'=> UC_DBCONNECT,
			),
			'ucenter'
		);
		$user = Db::read(UC_DBTABLEPRE.'members', $get['uid'], 'uid');
		if ($get['username'] == $user['username'] && $get['password'] == $user['password']) {
			header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
			Db::switchDb();
			// 是否存在相同用户名的账号
			$uid = Db::read(User::$tbl, $user['username'], 'username', 'uid');
			if ($uid) {//直接登录
				$ret = User::loginNoVerify($get['username']);
			} else {//创建用户(方法内已自动登录)
				$data = array(
					'no_verify_data'	=> true,
					'status'			=> 1,
					'username'			=> $get['username'],
					'password'			=> '',
					'uc_password'		=> $get['password'],
					'open_name'			=> 'ucenter',
					'open_uid'			=> $get['uid'],
					'open_username'		=> $get['username'],
					'accredit_time'		=> $get['password'],
				);
				$ret = User::create($data);
			}
			return API_RETURN_SUCCEED;
		} else {
			return API_RETURN_FAILED;
		}
	}

	/**
	 * 同步退出
	 * @param array $get
	 * @param array $post
	 */
	public static function synlogout($get, $post)
	{
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		
// 		$expireTime = time()-3600;
// 		setcookie('uid', '2', $expireTime, '/', $GLOBALS['_ck_domain']);
// 		setcookie('sid', '3', $expireTime, '/', $GLOBALS['_ck_domain']);
// 		var_export($_COOKIE);
// 		return API_RETURN_SUCCEED;
		User::unsetCookie();
		if (User::logout()) {
			return API_RETURN_SUCCEED;
		} else {
			return API_RETURN_FAILED;
		}
	}
	
	/**
	 * 更新应用列表
	 * @param array $get
	 * @param array $post
	 */
	public static function updateapps($get, $post) 
	{
		$UC_API = $post['UC_API'];
	
		//写 app 缓存文件
		$cachefile = UC_API_ROOT.'uc_client/data/cache/apps.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'apps\'] = '.var_export($post, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);
	
		//note 写配置文件
		$cfgFile = UC_API_ROOT.'ucenter.cfg.php';
		if(is_writeable($cfgFile)) {
			$configfile = trim(file_get_contents($cfgFile));
			$configfile = substr($configfile, -2) == '?>' ? substr($configfile, 0, -2) : $configfile;
			$configfile = preg_replace("/define\('UC_API',\s*'.*?'\);/i", "define('UC_API', '$UC_API');", $configfile);
			if($fp = @fopen($cfgFile, 'w')) {
				@fwrite($fp, trim($configfile));
				@fclose($fp);
			}
		}
	
		return API_RETURN_SUCCEED;
	}
	
	/**
	 * 更新敏感词列表坚持到底
	 * @param array $get
	 * @param array $post
	 */
	public static function updatebadwords($get, $post) 
	{
		$UC_API = $post['UC_API'];
		//写缓存文件
		$cachefile = UC_API_ROOT.'uc_client/data/cache/badwords.php';
		$fp = fopen($cachefile, 'w');
		$data = array();
		if(is_array($post)) {
			foreach($post as $k => $v) {
				$data['findpattern'][$k] = $v['findpattern'];
				$data['replace'][$k] = $v['replacement'];
			}
		}
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'badwords\'] = '.var_export($data, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);
		return API_RETURN_SUCCEED;
	}
	
	/**
	 * 
	 * @param array $get
	 * @param array $post
	 * @return string
	 */
	public static function updatehosts($get, $post) 
	{
		$cachefile = $this->appdir.'uc_client/data/cache/hosts.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'hosts\'] = '.var_export($post, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);
		return API_RETURN_SUCCEED;
	}
	

	private static function tests()
	{
		;
	}
}