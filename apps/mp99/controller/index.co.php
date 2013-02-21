<?php
/**
 * 公共控制器
 * Created		: 2012-05-30
 * Modified		: 2012-06-20
 * @link		: http://www.mp99.cn
 * @copyright	: (C) 2011 mp99.cn 
 * @version		: 1.0.0
 * @author		: Joseph Chen (chenliq@gmail.com)
 */
class Controller_Index extends Controller
{
	/**
	 * 构造函数，初始化操作
	 */
	public function __construct() 
	{
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Controller::index()
	 */
	public function index() 
	{
		$arr = [3234, 'abc'];
		echo '<pre>';
		exit;
		$mo = new Model_Article();
		//获取新闻公告列表
		$options = array(
				
		);
		$this->list = $mo->getList($options);
	}
	
	/**
	 * 标签云
	 */
	public function tagCloud() 
	{
		header('content-type:text/xml;charset='.$GLOBALS['_charset']);
		include APP_PATH.'data/xml/tagcloud.xml';
		exit;
	}
	
	/**
	 * 注册页面
	 */
	public function signup()
	{
		Core::redirect(Core::createUrl('/member/signup'));
	}
	
	/**
	 * 登录页面
	 */
	public function login()
	{
		Core::redirect(Core::createUrl('/member/login'));
	}
	
	/**
	 * 生成验证码
	 */
	public function authcode() 
	{
		if (empty($_GET['gif'])) {
			Tools::authcode();
		} else {
			Tools::gifAuthcode();
		}
	}
	
}