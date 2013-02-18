<?php
// 定义应用对应的绝对路径,包含控制器,模板(视图),模型,配置,缓存文件等,
define('APP_PATH', dirname(__DIR__).DIRECTORY_SEPARATOR);
$core	= dirname(dirname(dirname(__DIR__))).'/framework/Core.php';
require $core;
require APP_PATH.'config'.DS.'console.cfg.php';

// echo PHP_VERSION;
// echo "\n";
// echo PHP_VERSION_ID;
// echo "\n";
// exit;

var_export($_SERVER);

if ($_SERVER['argc'] < 2)
{
	echo 'module not exist.';
	echo "\n";
	exit;
}

// 判断模块文件是否存在
$mo = $_SERVER['argv'][1];
$moFile = __DIR__.'/'.$mo.'.php';
if (!is_file($moFile))
{
	echo 'module not exist.';
	echo "\n";
	exit;
}
require $moFile;
$moClass = ucfirst($mo).'Command';
$moObj = new $moClass;

// 调用方法
if (isset($_SERVER['argv'][2]))
{
	$do = $_SERVER['argv'][2];
} else {
	$do = 'index';
}

// 判断方法是否存在
if (!method_exists($moClass, $do))
{
	echo 'method not exist.';
	echo "\n";
	exit;
}

$params = array_slice($_SERVER['argv'], 3);
echo "\n";
var_export($params);
echo "\n";
call_user_func_array(array($moObj, $do), $params);





















