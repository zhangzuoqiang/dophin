<?php
/**
 * 命令行脚本
 *
 * @author Joseph Chen <chenliq@gmail.com>
 * @link http://www.dophin.co/
 * @copyright 2012-2013
 */

// fix for fcgi
defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));

defined('CMD_DEBUG') or define('CMD_DEBUG', true);

require_once(dirname(__FILE__).'/Core.php');

if(isset($config))
{
	
} else {
	
}

