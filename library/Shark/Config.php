<?php

class Shark_Config {

	public static function getConfig() {
		$key = Shark_Keys::CONFIG;
		if (!Zend_Registry::isRegistered($key)) {
			$filename = APPLICATION_PATH . DS . 'configs' . DS . 'application.ini';
			$config = new Zend_Config_Ini($filename, APPLICATION_ENV);
			Zend_Registry::set($key, $config);
		}
		return Zend_Registry::get($key);
	}
}