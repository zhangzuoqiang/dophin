<?php
// 定义web程序根目录的绝对路径(可独立于应用路径之外)
define('WEB_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR);
// 定义应用对应的绝对路径,包含控制器,模板(视图),模型,配置,缓存文件等,
define('APP_PATH', dirname(WEB_PATH).DIRECTORY_SEPARATOR);
// 定义框架核心路径
define('CORE_PATH', realpath('../../../framework/').DIRECTORY_SEPARATOR);
// 图片保存目录
define('IMG_PATH', WEB_PATH.'images/');
// 定义web程序HTML静态缓存目录
define('HTML_PATH', WEB_PATH.'html'.DIRECTORY_SEPARATOR);

require CORE_PATH.'Core.php';

require APP_PATH.'config'.DS.'development.cfg.php';
