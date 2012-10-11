<?php
// @codingStandardsIgnoreStart
class PagesController extends Shark_Controller_Action {

	protected $_routeName;
	protected $_param;
	protected $_path;
	protected $_prefix;
	protected $_tag;

	public function init() {
		parent::init();
		$this->_routeName = $this->getFrontController()->getRouter()->getCurrentRouteName();
		switch ($this->_routeName) {
			case 'services':
				$this->_path = 'services';
				$this->_param = 'service';
				break;
			case 'clients':
				$this->_path = 'clients';
				$this->_param = 'client';
				break;
			case 'pages':
			default:
				$this->_path = '';
				$this->_param = 'page';
				break;
		}
		$this->_prefix = $this->_param;
		$this->_tag = implode('_', array($this->_prefix, str_replace('-', '_', $this->getRequest()->getParam($this->_param))));
	}

	public function viewAction() {
		$script = implode('/', array($this->_path, $this->getRequest()->getParam($this->_param, null)));
		if (APPLICATION_ENV === 'production') {
			if (($content = $this->_cache->load($this->_tag)) === false) {
				$content = $this->view->render('pages' . DS . $script . '.phtml');
				$this->_cache->save($content, $this->_tag);
			}
			$this->view->content = $content;
		} else {
			$this->view->content = $this->view->render('pages'. DS . $script . '.phtml');
		}
	}
}
// @codingStandardsIgnoreEnd