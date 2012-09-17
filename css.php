<?php
ob_start();
header('Content-Type: text/css; charset: UTF-8');
header('Cache-Control: must-revalidate');
$offset = 60 * 60;
$expires = 'Expires: ' . gmdate('D, d M Y H:i:s', time() + $offset) . ' GMT';
header($expires);
if (isset($_GET['remote']) && !empty($_GET['remote'])) {
	$files = explode(',', $_GET['remote']);
	foreach ($files as $file) {
		readfile($file);
	}
}
if (isset($_GET['local']) && !empty($_GET['local'])) {
	$files = explode(',', $_GET['local']);
	foreach ($files as $file) {
		$filename = realpath(dirname(__FILE__) . $file);
		if (file_exists($filename)) {
			readfile($filename);
		}
	}
}
ob_end_flush();