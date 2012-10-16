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
		try {
			$tag = 'page_localmarketing_' . str_replace('-', '_', $page);
			if (APPLICATION_ENV === 'production') {
				if (($content = $this->cache->load($tag)) === false) {
					$content = $this->view->render('localmarketing/pages/' . $page . '.phtml');
					$this->cache->save($content, $tag);
				}
				$this->view->content = $content;
			} else {
				$this->view->content = $this->view->render('localmarketing/pages/' . $page . '.phtml');
			}
		} catch (Exception $e) {
			var_dump($e);
		}
	}
}