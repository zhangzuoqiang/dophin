<?php
class Controller_Article extends Controller
{
	
	public function initialize() 
	{
		$mo = new Model_Article();
		$this->statusList = $mo->statusList;
		unset($mo);
	}
	
	public function index() 
	{
		$mo = new Model_Article();
		$list = $mo->getList();
		var_export($list);
		exit;
	}
	
	/**
	 * 添加/修改页面
	 */
	public function detail() 
	{
		// 限制表单上传的最大值
		$this->MAX_FILE_SIZE = 1024000;
		$cmo = new Model('category');
		$cidList = $cmo->select();
		$this->cidList = Arr::simple($cidList);
		if (empty($_GET['id']) || !ctype_digit($_GET['id'])) {
			$detail = array();
		} else {
			$id = $_GET['id'];
			$mo = new Model_Article();
			$detail = $mo->read($id);
		}
		if ($detail)
		{
			$detail['post_time'] = date('Y-m-d H:i:s', $detail['post_time']);
			$this->detail = $detail;
		} else {
			$this->detail = array(
				'status'	=> 1,
				'views'		=> rand(1, 99),
				'post_time' => date('Y-m-d H:i:s')
			);
		}
	}
	
	/**
	 * 添加/修改文章
	 */
	public function save() 
	{
		if (!empty($_POST['id']) && !ctype_digit($_POST['id'])) {
			$this->failure(Core::getLang('invalid_request'));
		}
		// 如果有文件上传
		if ($_FILES['title_image']['error'] != UPLOAD_ERR_NO_FILE) {
			$options = array(
				'type'		=> 'image',
				'max_size'	=> 1024000,
				'save_path'	=> IMG_PATH.'article/',
				'save_rule'	=> '##Ymd/',
			);
	    	$upload = new UploadFile($options);
	    	// 上传错误提示错误信息
	    	if(!$upload->upload()) {
	    		$this->failure($upload->error());
	    	} else {// 上传成功 获取上传文件信息
	    		$_POST['title_image'] = $upload->fileList['title_image']['savename'];
	    	}
		} else {
			$_POST['title_image'] = '';
		}
		
		$_POST['editor_uid'] = $_SESSION['uid'];
		$_POST['editor'] = $_SESSION['username'];
		$_POST['post_time'] = strtotime($_POST['post_time']);
		
// 		if (!isset($_POST['brief'])) {
// 			$_POST['brief'] = '';
// 		}
// 		$_POST['content'] = '';

		$mo = new Model_Article();
		if ($mo->save($_POST)) {
			$this->success(Core::getLang('handle_success'), 'admin.php?a=article');
		} else {
			if ($mo->msg)
			{
				$msg = $mo->msg;
			} else {
				$msg = Core::getLang('save_article_data_fail');
			}
			$this->failure($msg);
		}
	}
}