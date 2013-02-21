<?php
class Controller_User extends Controller
{
	/**
	 * 用户登录表
	 * @var string
	 */
	public $tbl_login = 'user_login';
	
	public function initialize() 
	{
		$this->mo = new Model_User;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Controller::index()
	 */
	public function index() 
	{
// 		$this->list = $this->mo->field('uid,username,realname,status,address')
// // 						->where('username<>:username')
// // 						->params(array('username'=>'\'stee'))
// 						->where(array('username'=>'test14606'))
// 						->select();
		echo "<pre>";
// 		var_export($this->list);
// 		echo "\n";
// 		var_export($this->mo->getLastSql());
// 		phpinfo();
// 		exit;
	}
	
	/**
	 * 用户详细信息查看
	 */
	public function detail() 
	{
		if (isset($_GET['uid'])) {
			
		}
	}
	
	/**
	 * 保存用户表单信息
	 */
	public function doSave() 
	{
		$_POST = array (
// 			'uid'			=> '1',
			'username'		=> 'admin'.rand(),
			'password'		=> '333',
			'birth_year'	=> '1992',
			'birth_month'	=> '3',
			'birth_day'		=> '3',
			'gender'		=> '0',
			'status'		=> '0',
			'qq'			=> '4444444',
			'email'			=> 'tqjs@qq.com',
			'mobile'		=> '13333333333',
		);
		$data = array();
// 		if (empty($_POST['uid'])) {
// 			$this->json(Core::getLang('update_user_data_need_uid'), 0);
// 		}

		$this->mo->save();
		
		$salt = String::rand();
		$data = array(
			'uid'		=> $this->mo->lastInsertId,
			'username'	=> $this->mo->data['username'],
			'password'	=> User::hashPassword($_POST['password'], $salt),
			'salt'		=> $salt
		);
		$userLoginMo = new Model($this->tbl_login);
		$userLoginMo->add($data);
		
		echo '<pre>';
		var_export($this->mo->getLastSql());
		echo "\n";
		var_export($this->mo->errors());
		echo "\n";
		
		return false;
	}

}