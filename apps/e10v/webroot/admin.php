<?php
/*不建议这种调用方式，要同时保证后台引用js，css等静态文件同时保存一份到根目录而不仅仅在module*/
include dirname(__DIR__).'/module/admin/webroot/inc.php';
$_SESSION['uid'] = 1;
$_SESSION['username'] = 'admin';
Core::main('index');
