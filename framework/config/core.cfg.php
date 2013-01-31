<?php
/**
 * 系统基础配置
 */
$_debug					= false;
$_default_controller	= 'index';
$_charset				= 'utf-8';
$_version				= '0.0.1';
$_lang					= 'zh-cn';
$_theme_name			= 'default';
$_tpl_suffix			= '.html.php';
$_tpl_delimiter_left	= '{';
$_tpl_delimiter_right	= '}';
$_auto_compile_tpl		= true;// 是否自动编译模板
$_template_engine		= 'dophin';
$_template_auto_compile	= false;
$_output_process_time	= false;
$_mathcode_start_value	= 8584334;

/**
 * 数据库配置
 * 使用sqlite时才用到db_path
 */
$_db_path		= '';
$_db_driver		= 'mysql';
$_db_host		= 'localhost';
$_db_name		= 'antphp';
$_db_user		= 'antphp';
$_db_pass		= '123456';
$_db_charset	= 'utf8';
$_db_persistent	= true;
$_tbl_pre		= '';
	
/**
 * 缓存配置(memcahe等)
 */
$_cache_enable		= true;
$_cache_type		= 'redis';
$_cache_host		= 'localhost';
$_cache_port		= '6379';
$_cache_auth		= '2012+abc!=2010';

/**
 * 缓存控制
 */
// 用户缓存数据有效时间（单位：秒）
$_cache_user_expire	= 43200;

	
/**
 * cookie相关
 */
$_cookie_expire			= '3600';
$_cookie_path			= '/';
$_cookie_domain			= '.dophin.co';
// cookie中用户信息过期时间
$_cookie_user_expire	= '3600';

/**
 * session相关
 */
// session存活时间
$_session_lifetime	= '1440';
// 是否使用session保存会员信息
$_session_user		= false;
$_session_type		= 'db';
// $_session_path		= 'tcp://127.0.0.1:11211';

/**
 * 用户相关
 */
// 用户名是否可以为纯数字
$_username_only_digit	= false;
$_username_minlen		= 3;
$_username_maxlen		= 16;
$_password_minlen		= 3;
$_password_maxlen		= 16;

/**
 * 充值
 */
// 订单号类型：1-纯数字（含日期时间），2-sha1加密字符串，3-随机数加MD5唯一id字符串
$_orderno_type	= 1;


/**
 * 系统其他
 */
$_same_password_key = 'EZq2zSdpBsZu8VmwhTnCJC';
$_id_crypt_cipher = 'TjSjMhhlcKdvtp';
$_id_crypt_salt = 'LkYkO7M';


