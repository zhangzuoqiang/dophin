<?php
include '../admin/inc.php';
$_SESSION['uid'] = 1;
$_SESSION['username'] = 'admin';
Core::main('index');
