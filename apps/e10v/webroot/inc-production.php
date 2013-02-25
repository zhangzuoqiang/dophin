<?php
// 定义web程序根目录的绝对路径(可独立于应用路径之外)
define('WEB_PATH', dirname(__FILE__).'/');
// 定义应用对应的绝对路径,包含控制器,模板(视图),模型,配置,缓存文件等,
define('APP_PATH', dirname(WEB_PATH).'/');
// 定义框架核心路径
define('CORE_PATH', dirname(dirname(APP_PATH)).'/framework/');
// 图片保存目录
define('IMG_PATH', WEB_PATH.'images/');
// 定义web程序HTML静态缓存目录
define('HTML_PATH', WEB_PATH.'html/');

require CORE_PATH.'Core.php';
