<?php

class Shark_Controller_Action extends Shark_Controller_Action_Abstract {
	protected $_cache;

	public function init() {
		if (APPLICATION_ENV === 'production') {
			$this->_cache = Zend_Registry::get('Zend_Cache');
			if ($this->getRequest()->getParam('clear_cache', null) === 'true') {
				$this->_cache->clean(Zend_Cache::CLEANING_MODE_ALL);
			}
		}
	}
}
