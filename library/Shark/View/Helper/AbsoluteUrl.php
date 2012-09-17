<?php

class Shark_View_Helper_AbsoluteUrl extends Zend_View_Helper_Abstract {

	public function absoluteUrl($uri = null) {
		$request = Zend_Controller_Front::getInstance()->getRequest();
		$serverUrl = $this->view->serverUrl();
		$requestUri = $request->getRequestUri();
		if ($uri !== null) {
			$requestUri = $this->view->baseUrl($uri);
		}
		return $serverUrl . $requestUri;
	}
}