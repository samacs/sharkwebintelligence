<?php

class Shark_View_Helper_Clients extends Zend_View_Helper_Abstract {

	public function clients($name = null, $count = 0) {
		$filename = APPLICATION_PATH . DS . 'configs' . DS . $this->view->language . '.clients.ini';
		$config = new Zend_Config_Ini($filename, 'clients');
		$clients = $config->clients;
		if ($name !== null) {
			$client = $client->{$name};
			var_dump($client);
		} else {
			$this->view->assign(array(
				'clients' => $clients,
				'count' => $count,
			));
			return $this->view->render('clients/list.phtml');
		}
	}
}