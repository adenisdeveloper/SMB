<?php

require('vendor/autoload.php');

$host = 'localhost';
$user = 'test';
$workgroup = 'test';
$password = 'test';
$share = 'test';

$auth = new \Tecnovix\SMB\BasicAuth($user, $workgroup, $password);
$serverFactory = new \Tecnovix\SMB\ServerFactory();

$server = $serverFactory->createServer($host, $auth);

$share = $server->getShare($share);

$files = $share->dir('/');
foreach ($files as $file) {
	echo $file->getName() . "\n";
}
