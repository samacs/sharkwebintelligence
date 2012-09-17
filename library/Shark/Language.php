<?php

class Shark_Language {

	private static $_instance;

	private $_languages = array();

	public static function getInstance() {
		if (null === self::$_instance) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct() {
		$this->_languages = $this->_getLanguagesList();
	}
	
	public function getLanguages() {
		return $this->_languages;
	}

	private function _getLanguagesList() {
		$path =APPLICATION_PATH . DS . '..' . DS . 'data' . DS . 'locales';
		$languages = Shark_File::getSubDirectories($path);
		return $languages;
	}
}