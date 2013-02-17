<?php
/**
 * 系统基础配置
 */
$_debug					= true;
$_output_process_time	= true;

/**
 * 数据库配置
 * 使用sqlite时才用到db_path
 */
$_db_path		= '';
$_db_driver		= 'mysql';
$_db_host		= 'localhost';
$_db_name		= 'zf';
$_db_user		= 'admin';
$_db_pass		= '123456';
$_db_charset	= 'utf8';
$_db_persistent	= true;
$_tbl_pre		= '';
	
/**
 * 缓存配置(memcahe等)
 */
$_cache_enable		= false;
$_cache_type		= 'redis';
$_cache_host		= 'localhost';
$_cache_port		= '6378';
$_cache_auth		= '2012+abc!=2010';
	
/**
 * cookie相关
 */
$_cookie_expire		= '3600';
$_cookie_path		= '/';
$_cookie_domain		= '.fzzf.co';

/**
 * session相关
 */
$_session_user		= false;
$_session_lifetime	= '1440';
$_session_type		= 'db';
// $_session_type		= 'memcache';
// $_session_path		= 'tcp://127.0.0.1:11211';

/**
 * 用户相关
 */
// 用户名是否可以为纯数字
$_username_only_digit = false;

/**
 * 经济系统相关
 */
// 人民币对应金币的汇率
$_money_gold_rate = 10;


/**
 * 链接地址
 */
// $_img_url = 'http://img.house.x/';
$_img_url = 'http://www.fzzf.co/images/';




/**
 * 系统其他
 */
$_same_password_key = 'EZq2zSdpBsZu8VmwhTnCJC';
$_id_crypt_key = '5WrHs7EcA4n';
$_articleid_crypt_key = 'Zfi2xI9NecmjT';
$_teleplayid_crypt_key = 'n8UqgJwZ7bKl';






