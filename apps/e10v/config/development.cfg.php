<?php
ini_set('display_errors', 1);
error_reporting(2047);
/**
 * 系统基础配置
 */
$_debug					= true;
$_output_process_time	= false;

$_db	= array(
	'path'			=> '',
	'driver'		=> 'mysql',
	'host'			=> 'localhost',
	'name'			=> 'e10v',
	'user'			=> 'admin',
	'pass'			=> '123456',
	'charset'		=> 'utf8',
	'persistent'	=> true,
	'tbl_pre'		=> '',
);

/**
 * 链接地址
 */
$_img_url = 'http://e10v.com/images/';
$_www_url = 'http://e10v.com/';