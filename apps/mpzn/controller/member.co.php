<?php
/**
 * 会员相关控制器
 * Created		: 2012-05-30
 * Modified		: 2012-06-20
 * @link		: http://www.mpzn.co
 * @copyright	: (C) 2011 mpzn.co 
 * @author		: Joseph Chen (chenliq@gmail.com)
 */
class Controller_Member extends Controller
{
	/**
	 * 用户登录表
	 * @var string
	 */
	public $tbl_login = 'user_login';
	
	/**
	 * 控制器载入后执行
	 */
	public function initialize()
	{
		$this->mo = new Model_User;
	}
	
	/**
	 * 会员中心首页
	 * @see Controller::index()
	 */
	public function index() 
	{
		if (!User::isLogined()) {
// 			$this->redirect('/index.php?co=member&do=login', 0);
		}
		var_export(User::$data);
// 		echo $this->getThemeFile();
// 		exit;
	}
	
	
	/*************** 处理操作请求 ***************/
	
	/**
	 * 处理注册请求
	 */
	public function doSignup()
	{
		// 检测密码数据是否合法
		if (!isset($_POST['password']) || !$this->mo->checkpass($_POST['password'])) {
			$this->json($this->mo->msg, 0);
		}
		$_POST['regip']		= Ip::getIntIp();
		$_POST['regtime']	= time();
		
		// 添加用户详细信息
		if (!$this->mo->save()) {
			$this->json($this->mo->msg, 0);
		}

		// 添加用户登录表的数据
		$salt = String::rand();
		$password = User::hashPassword($_POST['password'], $salt);
		$loginData = array(
			'uid'		=> $this->mo->lastInsertId,
			'username'	=> $_POST['username'],
			'password'	=> $password,
			'salt'		=> $salt
		);
		$loginMo = new Model($this->tbl_login);
		if (!$loginMo->add($loginData)) {
			$this->json($loginMo->msg, 0);
		}
		
		$this->json(Core::getLang('signup_success'));
	}
	
	/**
	 * 登录表单操作
	 */
	public function doLogin()
	{
		$codeValue = md5(md5($_SERVER['SERVER_NAME']).strtoupper($_POST['authcode']));
		// 验证码不正确
		if (!isset($_COOKIE['login_authcode']) || $codeValue != $_COOKIE['login_authcode']) {
			setcookie('login_authcode', false);
			$this->json(Core::getLang('incorrect_verification_code'), 2);
		}
		// 账号密码验证失败
		if (!User::verify($_POST['username'], $_POST['password'])) {
			$this->json(User::$msg, 0);
		}
		setcookie('login_authcode', false);
		
		// 成功输出默认数据
		$this->json(User::$msg);
	}
	
	/**
	 * 修改密码操作
	 */
	public function doChangePwd() 
	{
		// 未登录，不能进行操作
		if (!User::isLogined()) {
			$this->json(Core::getLang('handle_failed_without_logined'));
		}
		// 账号密码验证失败
		if (!User::verify($_SESSION['username'], $_POST['password'])) {
			$this->json(User::$msg, 0);
		}
	}
	
}