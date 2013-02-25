<?php
/**
 * 文章
 * Created		: 2012-11-08
 * Modified		: 2012-11-08
 * @link		: http://www.e10v.com
 * @copyright	: (C) 2011 e10v.com 
 * @version		: 1.0.0
 * @author		: Joseph Chen (chenliq@gmail.com)
 */
class Controller_Article extends Controller
{
	/**
	 * 构造函数，初始化操作
	 */
	public function __construct() 
	{
		// 设置文章ID加密密钥
		String::idCryptKey($GLOBALS['_articleid_crypt_key']);
		$mo = new Model_Article();
		$this->statusList = $mo->statusList;
		unset($mo);
	}
	
	/**
	 * 显示文章列表
	 */
	public function index() 
	{
		$mo = new Model_Article();
		$options = array(
			'has_page'	=> true,
			'status'	=> 1,
			'limit'		=> 20,
			'post_time'	=> '2012-6-20'
		);
		if (isset($_GET['keyword'])) {
			$options['keyword'] = $_GET['keyword'];
		}
		$this->list = $mo->getList($options);
		$this->pagePanel = $mo->pagePanel;
	}

	/**
	 * 显示文章信息
	 */
	public function detail()
	{
		if (!empty($_GET['url']))
		{
			$start = strrpos($_GET['url'], '/');
			if (false === $start)
			{
				$start = -1;
			}
			if (false === strpos($_GET['url'], '.html'))
			{
				$str = substr($_GET['url'], $start+1);
			} else {
				$str = substr($_GET['url'], $start+1, -5);
			}
			$_GET['id'] = $id = UMath::idDecrypt($str);
		}
		if (empty($_GET['id']) || !ctype_digit($_GET['id'])) {
			$this->header404();
			exit;
		} else {
			$id = $_GET['id'];
		}
		$mo = new Model_Article();
		if (!$detail)
		{
			$this->header404();
			exit;
		}
		// 有跳转链接，直接跳转到目标网址
		if ($detail['redirect'])
		{
			header('Location:'.$detail['redirect']);
			exit;
		}
		if ($detail['html_file'])
		{
			if (!empty($_GET['make_html']) && is_file($htmlFile=$mo->getHtmlFile($id, $detail['html_file'])))
			{
				$htmlUrl = str_replace(WEB_PATH, '/', $htmlFile);
				header('Location:'.$htmlUrl);
				exit;
			} else {
				$_GET['make_html'] = true;
			}
		} else {
			$_GET['make_html'] = true;
			$detail['html_file'] = UMath::idCrypt($id);
			$data = array(
				'id'		=> $id,
				'html_file' => $detail['html_file']
			);
			$mo->save($data);
		}
		
		$this->detail = $detail;
		
		$_GET['make_html'] = true;
		$_GET['html_display'] = false;
		$_GET['html_file'] = $mo->getHtmlFile(
				$id,
				$detail['html_file']
		);
		$htmlUrl = str_replace(WEB_PATH, '/', $_GET['html_file']);
		$this->display();
		header('Location:'.$htmlUrl);
		return false;
	}
	
}