<?php
set_time_limit(0);
ini_set('max_execution_time', 30000);
define('NO_AUTO_DISPATCH', true);
require 'inc.php';
Core::main();
// Tools::authcode();
// exit;
header('content-type:text/plain;charset=utf-8');
// echo '<pre>';
// echo time();
echo "\n\n";

// $_SESSION['uid'] = 10001;
// $_SESSION['username'] = 'clq329';

echo PHP_SAPI;

echo "\n";
echo php_logo_guid();



echo "\n";

// $key = '5ZRsfdv2ex';
// $salt = 'v3DkMwhTnC';
// echo str_shuffle(substr(UMath::$radixStr, 0, 62));
$start = microtime(1);
echo "\n";
$id = 3212;
echo $id;
echo "\n";
// $str = String::idEncode($id);
// $str = String::idEncode64($id);
$str = String::idCrypt($id);
echo $str;
echo "\n=================\n";
// echo String::idDecode($str);
// echo String::idDecode64($str);
echo String::idDeCrypt($str);
echo "\n";
echo microtime(1) - $start;
// echo UMath::dec2any(2821109907456, 10, 36);
exit;


$id = (string)$id;
$str = '';
$pos = 0;
		$len = strlen($id);
		$keyLen = strlen($key);
		if ($len < $keyLen)
		{
			$id .= '|'.substr($salt, 0, $keyLen-$len);
			$len = strlen($id);
		}
		for ($i=0; $i<$len; $i++)
		{
			if (isset($key[$i]))
			{
				$char = $key[$i];
				$pos++;
			} else {
				$pos = 0;
				$char = $key[0];
			}
			$c = $id[$i] ^ $char;
			$str .= $c;
		}
		$str = base64_encode($str);
echo $str;
echo "\n";

$id = '';
$pos = 0;
		$str = base64_decode($str);
		$len = strlen($str);
		for ($i=0; $i<$len; $i++)
		{
			if (isset($key[$i]))
			{
				$char = $key[$i];
				$pos++;
			} else {
				$pos = 0;
				$char = $key[0];
			}
			$c = $str[$i] ^ $char;
			if ($c == '|')
			{
				break;
			}
			$id .= $c;
		}
echo $id;


echo "\n";
echo microtime(1) - APP_BEGIN_TIME;
