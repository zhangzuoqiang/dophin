<?php
// 定义应用对应的绝对路径,包含控制器,模板(视图),模型,配置,缓存文件等,
define('APP_PATH', dirname(__DIR__).DIRECTORY_SEPARATOR);
$core	= dirname(dirname(dirname(__DIR__))).'/framework/Core.php';
require $core;
require APP_PATH.'config'.DS.'console.cfg.php';

var_export($_SERVER);