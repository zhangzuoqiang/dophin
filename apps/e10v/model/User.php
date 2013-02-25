<?php
/**
 * 用户模型
 * Created		: 2012-06-20
 * Modified		: 2012-06-20
 * @version		: 1.0.0
 * @author		: Joseph Chen (chenliq@gmail.com)
 */
class Model_User extends Model
{
	/**
	 * 表名(不含前缀)
	 * @var string
	 */
	public $_tbl = 'user';
	/**
	 * 表名
	 * @var string
	 */
	public $tbl = 'user';
	/**
	 * 用户登录验证使用的表
	 * @var string
	 */
	public $tbl_login = 'user_login';
	/**
	 * 用户信息是否缓存
	 * @var boolean
	 */
	public $useCache = true;
	/**
	 * 用户信息缓存时间（单位：秒）
	 * @var int
	 */
	public $cacheExpire = 86400;
	/**
	 * 提示信息（包含但不限于错误提示）
	 * @var string
	 */
	public $msg = '';
	/**
	 * 验证规则
	 * @var array
	 */
	protected $validation = array(
		'username'	=> array('checkuser'),
		'password'	=> array('checkpass'),
	);
	
	/**
	 * 获取用户信息
	 */
	public function read($uid=null, $field='uid') 
	{
		if (!$uid) {
// 			if ($_SESSION['UID']) {
// 				$uid = $_SESSION['UID'];
// 			}
		}
		return parent::read($uid, 'uid');
	}
	
	/**
	 * 检查用户名是否合法
	 * @param string $username
	 * @return boolean
	 */
	public function checkuser($username) 
	{
		if (is_null($username)) {
			$this->msg = Core::getLang('username_is_required');
			return false;
		}
		// 不能全数字
		if (!$GLOBALS['_username_only_digit'] && ctype_digit((string)$username)) {
			$this->msg = Core::getLang('username_cannot_only_digit');
			return false;
		}
		// 用户名长度受限
		$len = mb_strlen($username);
		if ($len<$GLOBALS['_username_minlen'] || $len>$GLOBALS['_username_maxlen']) {
			$replacement = array(
					'{minlen}'	=> $GLOBALS['_username_minlen'], 
					'{maxlen}'	=> $GLOBALS['_username_maxlen']
			);
			$this->msg = Core::getLang('username_length_out_of_bound', $replacement);
			return false;
		}
		// 含有非法字符
		if (strtoupper($GLOBALS['_charset'])=='UTF-8' 
					&& !preg_match('/^[\w\x{4e00}-\x{9fa5}]+$/u', $username)) {
			$this->msg = Core::getLang('username_character_invalid');
			return false;
		} else if (strtoupper($GLOBALS['_charset'])=='GBK' 
					&& !preg_match('/^[\w'.chr(0xa1).'-'.chr(0xff).']+$/', $username)) {
			$this->msg = Core::getLang('username_character_invalid');
			return false;
		}
		return true;
	}
	
	/**
	 * 检查密码是否合法
	 * @param string $password
	 * @return boolean
	 */
	public function checkpass($password)
	{
		// 密码不能为空
		if (empty($_POST['password'])) {
			$this->msg = Core::getLang('password_is_required');
			return false;
		}
		$passlen = mb_strlen($_POST['password']);
		if ($passlen<$GLOBALS['_password_minlen'] || $passlen>$GLOBALS['_password_maxlen']) {
			$replacement = array(
					'{minlen}'	=> $GLOBALS['_password_minlen'], 
					'{maxlen}'	=> $GLOBALS['_password_maxlen']
			);
			$this->msg = Core::getLang('password_length_out_of_bound', $replacement);
			return false;
		}
		return true;
	}
}