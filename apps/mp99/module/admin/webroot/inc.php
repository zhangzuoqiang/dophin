<?php
// 定义web程序根目录的绝对路径(可独立于应用路径之外)
define('WEB_PATH', __DIR__.'/');
// 定义应用对应的绝对路径,包含控制器,模板(视图),模型,配置,缓存文件等,
define('APP_PATH', dirname(dirname(dirname(WEB_PATH))).'/');
// 定义框架核心路径
define('CORE_PATH', dirname(dirname(APP_PATH)).'/framework/');

include CORE_PATH.'Core.php';

require APP_PATH.'config/development.cfg.php';

// 控制器路径
define('CO_PATH', dirname(WEB_PATH).'/controller/');
// 模型路径
define('MO_PATH', APP_PATH.'model/');
// 后台的模板路径
define('VIEW_PATH', APP_PATH.'view/admin/');
// 上传的图片保存目录
define('IMG_PATH', APP_PATH.'images/');
Core::loadLang('admin');

