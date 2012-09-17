<?php

class Shark_View_Helper_Projects extends Zend_View_Helper_Abstract {
	
	public function projects($name = null, $count = 0) {
		$filename = APPLICATION_PATH . DS . 'configs' . DS . $this->view->language . '.projects.ini';
		$config = new Zend_Config_Ini($filename, 'projects');
		$projects = $config->projects;
		if ($name !== null) {
			$project = $project->{$name};
			var_dump($project);
		} else {
			$this->view->assign('projects', $projects);
			return $this->view->render('projects/list.phtml');
		}
	}
}