<?php
//如果有文件上传
if ($_FILES['image']['error'] != UPLOAD_ERR_NO_FILE || $_FILES['title_image']['error'] != UPLOAD_ERR_NO_FILE) {
	$options = array(
		'type'		=> 'image',
		'max_size'	=> array(
			'image'			=> 102400,
			'title_image'	=> 204800
		),
		'save_path'	=> array(
			'image'			=> IMG_PATH.'test/',
			'title_image'	=> IMG_PATH.'article/'
		),
		'save_rule'	=> array(
			'image'			=> '##Ymd/',
			'title_image'	=> '',
		),
	);
	$upload = new UploadFile($options);
	if(!$upload->upload()) {// 上传错误提示错误信息
		$this->json($upload->error(), 0);
	} else {// 上传成功 获取上传文件信息
		$_POST['title_image'] = $upload->fileList['title_image']['savename'];
	}
}