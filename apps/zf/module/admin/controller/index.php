<?php
class Controller_Index extends Controller
{

	/*************** 页面显示 ***************/
	/**
	 * 首页
	 */
	public function index() 
	{
		$this->list = array('test');
	}
	
	
	/*************** 处理操作请求 ***************/
	/**
	 * 登录表单操作
	 */
	public function doLogin()
	{
		$codeValue = md5(md5($_SERVER['SERVER_NAME']).strtoupper($_POST['authcode']));
		// 验证码不正确
		if (!isset($_COOKIE['admin_authcode']) || $codeValue != $_COOKIE['admin_authcode']) {
			setcookie('admin_authcode', false);
			$this->json(Core::getLang('incorrect_verification_code'), 2);
		}
		// 账号密码验证失败
		if ('admin' == $_POST['username']) {
			include APP_PATH.'config'.DS.'adminpass.cfg.php';
			if ($_admin_pass == User::hashPassword($_POST['password'], $_admin_salt)) {
				$_SESSION['uid'] = 1;
				$_SESSION['username'] = 'admin';
			} else {
				$this->json(Core::getLang('password_incorrect'), 0);
			}
		} else if (!User::verify($_POST['username'], $_POST['password'])) {
			$this->json(User::$msg, 0);
		}
		setcookie('admin_authcode', false);
		
		// 成功输出默认数据
		$this->json();
	}
	
	/**
	 * 生成验证码
	 */
	public function authcode()
	{
		$ckpre = 'admin';
		if (empty($_GET['gif'])) {
			Tools::authcode($ckpre);
		} else {
			Tools::gifAuthcode($ckpre);
		}
	}
}