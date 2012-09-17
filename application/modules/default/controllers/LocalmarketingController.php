<?php

class LocalmarketingController extends Shark_Controller_Action
{
	public function indexAction()
	{

	}

	public function viewAction()
	{
		$request = $this->getRequest();
		$page = $request->getParam('page', null);
		if (null === $page || empty($page)) {
			return $this->render('index');
		}
		return $this->render('pages/' . $page);
	}
}