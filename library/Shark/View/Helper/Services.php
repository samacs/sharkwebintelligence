<?php

class Shark_View_Helper_Services extends Zend_View_Helper_Abstract {
	
	public function services($name = null, $count = 0) {
		$language = Zend_Registry::get('Zend_Locale')->getLanguage();
		$filename = APPLICATION_PATH . DS . 'configs' . DS . $language . '.services.ini';
		$config = new Zend_Config_Ini($filename, 'services');
		$services = $config->services;
		if ($name !== null) {
			$service = $services->{$name};
		} else {
			$this->view->assign('services', $services);
			return $this->view->render('services/list.phtml');
		}
	}
}