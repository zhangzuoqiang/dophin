<?php
// 定义应用对应的绝对路径,包含控制器,模板(视图),模型,配置,缓存文件等,
define('APP_PATH', dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR);
// 定义框架核心路径
define('CORE_PATH', realpath('../../../framework/').DIRECTORY_SEPARATOR);

include CORE_PATH.'Core.php';

// 定义web程序根目录的绝对路径(可独立于应用路径之外)
define('WEB_PATH', APP_PATH.'webroot'.DS);
// 控制器路径
define('CO_PATH', APP_PATH.'admin/controller'.DIRECTORY_SEPARATOR);
// 模型路径
define('MO_PATH', APP_PATH.'model'.DIRECTORY_SEPARATOR);
// 后台的模板路径
define('VIEW_PATH', APP_PATH.'view'.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR);
// 上传的图片保存目录
define('IMG_PATH', APP_PATH.'images/');
Core::loadLang('admin');

