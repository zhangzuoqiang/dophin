<?php
/**
 * 绑定二级域名: admin.xxx.com 调用
 */
include 'inc.php';
$_SESSION['uid'] = 1;
$_SESSION['username'] = 'Administrator';
Core::main();
