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
	'name'			=> 'mp99',
	'user'			=> 'admin',
	'pass'			=> '123456',
	'charset'		=> 'utf8',
	'persistent'	=> true,
	'tbl_pre'		=> '',
);

/**
 * 链接地址
 */
$_img_url = 'http://mp99.cn/images/';
$_www_url = 'http://mp99.cn/';