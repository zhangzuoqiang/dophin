<?php
/**
 * 控制器抽象类
 * 
 * Created		: 2011-06-05
 * Modified		: 2012-08-23 
 * @link		: http://www.dophin.co
 * @copyright	: (C) 2011-2012 Dophin 
 * @version		: 1.0.0
 * @author		: Joseph Chen (chenliq@gmail.com)
 */
return array(
	'handle_success'	=> '操作成功',
	'handle_failed'		=> '操作失败',
	
	'invalid_request' 				=> '您的请求无效。',
	'incorrect_verification_code'	=> '验证码不正确.',
	
	'controller_not_exist'				=> '请求的控制器 "{controller}" 不存在！',
	'controller_method_not_exist'		=> '请求的控制器方法 "{method}" 不存在！',
	'The template file opened fail.'	=> '模板文件无法打开',
	'template_file_not_exist'			=> '模板文件 "{template_file}" 不存在',
	
	'data_must_be_array_or_object'	=> '给定的数据必须是一个数组或一个对象',
	'ipdata_library_need_update'	=> '有最新的IP库需要更新',
	
	/**
	 * model
	 */
	'field_is_required'			=> '字段{field}必须填写。',
	'field_invalid'				=> '字段{field}数据"{value}"非法。',
		
	/**
	 * upload
	 */
	'upload_file_extension_not_allowed' 
			=> '文件 "{file}" 无法被上传. 只有后缀名如下的文件是被允许的: {extensions}。',
	'upload_file_too_large' 
			=> '文件 "{file}" 太大. 文件大小不能超过 {limit} 字节。',
	'upload_file_too_small' 
			=> '文件 "{file}" 太小. 文件大小不能少于 {limit} 字节。',
	'upload_file_extension_not_match_mime_type' 
			=> '文件"{file}"的后缀名与文件类型"{mime-type}"不匹配。',
	'upload_file_extension_not_match_mime_type' 
			=> '文件"{file}"的后缀名与文件类型"{mime-type}"不匹配。',
	'upload_file_failed_to_write_to_disk' 
		=> '无法将已上传的文件 "{file}" 写入硬盘。',
	'upload_file_stopped_by_extension' => '文件上传被 extension 所停止。',
	'upload_path_invalid' => '上传文件保存路径 "{path}" 不是一个有效的目录。',
		
	/**
	 * IP库
	 */
		
	/**
	 * 用户相关
	 */
	'login_success'					=> '登录成功。',
	'signup_success'				=> '注册成功。',
	'handle_failed_without_logined'	=> '未登录状态不允许操作。',
	'username_not_exist'			=> '用户名不存在。',
	'password_incorrect'			=> '密码错误。',
	'username_cannot_only_digit'	=> '用户名不能为纯数字。',
	'username_character_invalid'	=> '用户名只能由以下字符组成：汉字、英文字母、数字、下划线。',
	'username_length_out_of_bound'	=> '用户名长度只能是{minlen}~{maxlen}个字符。',
	'username_is_required'			=> '用户名必须填写。',
	'password_is_required'			=> '密码必须填写。',
	'password_length_out_of_bound'	=> '密码长度只能是{minlen}~{maxlen}个字符。',
);