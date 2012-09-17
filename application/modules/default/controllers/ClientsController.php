<?php
class ClientsController extends Shark_Controller_Action {

	public function indexAction() {

	}
	
	public function viewAction() {
		$client = $this->getRequest()->getParam('client');
		if (null === $client) {
			return $this->render('index');
		}
	}
}