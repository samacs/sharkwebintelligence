<?php
class Shark_View_Helper_TwitterBootstrapMenu extends Zend_View_Helper_Abstract {

	public function twitterBootstrapMenu($menu = 'mainnav', $type = 'navbar', $submenu = null) {
		$config = new Zend_Config_Xml(APPLICATION_PATH . DS . 'configs' . DS . 'navigation.xml', $menu);
		$container = new Zend_Navigation($config);
		if ($submenu) {
			$container = $container->findOneByLabel($submenu);
		}

		switch ($type) {
			case 'list':
				return $this->_buildList($container);
			case 'navbar':
			default:
				return $this->_buildNavbar($container);
		}
	}

	private function _buildList($container) {
		$output = '<ul class="nav">';
		foreach ($container as $page) {
			$output .= $this->_buildItem($page);
		}
		$output .= '</ul>';
		return $output;
	}

	private function _buildItem($page) {
		$output = '';
		$itemClasses = array();
		$linkClasses = array();
		$hasSubItems = count($page->pages) > 0;
		$href = $page->href;
		if ($page->isActive()) {
			$itemClasses[] = 'active';
		}
		if ($hasSubItems) {
			//$href = '#';
			$itemClasses[] = 'haschild';
			$linkClasses[] = 'haschild';
		}
		$output .= '<li class="' . implode(' ', $itemClasses) . '">';
		$output .= $this->_buildAnchor($href, $page->title, $page->label);
		if ($hasSubItems) {
			$output .= '<ul class="sub-items">';
			foreach ($page->pages as $subPage) {
				$output .= $this->_buildItem($subPage);
			}
			$output .= '</ul>';
		}
		$output .= '</li>';
		return $output;
	}

	private function _buildNavbar($container) {
		$output = '
		<div class="navbar">
			<!--
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<div class="nav-collapse">
			-->
			<div>
				<ul class="nav">';
		foreach ($container as $page) {
	 		$output .= $this->_buildItemBootstrap($page);
		}
		$output .= '
				</ul>
			</div>
		</div>';
		return $output;
	}

	private function _buildItemBootstrap($page) {
		$output = '';
		$dataAttributes = array();
		$itemClasses = array();
		$linkClasses = array();
		$hasSubItems = count($page->pages) > 0;

		$href = $page->href;

		if ($page->isActive(true)) {
			$itemClasses[] = 'active';
		}
		if ($hasSubItems) {
			//$href = '#';
			$itemClasses[] = 'dropdown';
			$linkClasses[] = 'dropdown-toggle';
			$dataAttributes['toggle'] = 'dropdown';
		}

		$output .= '<li class="' . implode(' ', $itemClasses ) . '">';
		$output .= $this->_buildAnchor($href, $page->title, $page->label, $dataAttributes, $linkClasses);
		if ($hasSubItems) {
			$output .= '<ul class="dropdown-menu">';
			foreach ($page->pages as $subPage) {
				$output .= $this->_buildItemBootstrap($subPage);
			}
			$output .= '</ul>';
		}
		$output .= '</li>';
		return $output;
	}

	private function _buildAnchor($href, $title, $label, $dataAttributes = array(), $classes = array()) {
		$data = '';
		foreach ($dataAttributes as $key => $value) {
			$data .= 'data-' . $key . '="' . $value . '"';
		}
		$class = 'class="' . implode(' ', $classes) . '"';
		return '<a href="' . $href . '" title="' . $title . '" ' . $data . ' ' . $class . '>' . $label . '</a>';
	}
}