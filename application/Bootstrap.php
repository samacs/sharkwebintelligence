<?php
// @codingStandardsIgnoreStart
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

	protected function _initSession() {
		Zend_Session::setOptions(Shark_Config::getConfig()->session->toArray());
		Zend_Session::start();
	}

	protected function _initCache() {
		$frontendOptions = array(
			'lifetime' => 7200,
			'automatic_serialization' => true,
		);
		$backendOptions = array(
			'cache_dir' => APPLICATION_PATH . DS . '..' . DS . 'data' . DS . 'cache',
		);
		$cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
		Zend_Registry::set('Zend_Cache', $cache);
	}

	protected function _initAutoloader()
	{
		$frontController = $this->getResource('frontController');
		$autoloader = Zend_Loader_Autoloader::getInstance();
		new Zend_Loader_Autoloader_Resource(
		    array(
				'basePath' => APPLICATION_PATH . DS . 'modules' . DS . 'default',
				'namespace' => '',
				'resourceTypes' => array(
					'form' => array(
						'path' => 'forms',
						'namespace' => 'Form',
					),
					'model' => array(
						'path' => 'models',
						'namespace' => 'Model',
					),
					'table' => array(
						'path' => 'tables',
						'namespace' => 'Table',
					),
					'entity' => array(
						'path' => 'entities',
						'namespace' => 'Entity',
					),
				),
			)
		);
	}

	protected function _initTranslate() {
		$language = Shark_Config::getConfig()->site->language;
		$locale = new Zend_Locale($language);
		Zend_Registry::set('Zend_Locale', $locale);
		$translate = new Zend_Translate(array(
			'adapter' => 'ini',
			'content' => APPLICATION_PATH . DS . '..' . DS . 'data' . DS . 'locales',
			'scan' => Zend_Translate::LOCALE_DIRECTORY,
			'locale' => $locale,
		));
	}

	protected function _initRoutes() {

		$router = Zend_Controller_Front::getInstance()->getRouter();

		// Default
		$route = new Zend_Controller_Router_Route_Static(
			'/',
			array(
				'module' => 'default',
				'controller' => 'index',
				'action' => 'index',
			)
		);
		$router->addRoute('default', $route);

		// Pages
		$route = new Zend_Controller_Router_Route(
			'/:page',
			array(
				'module' => 'default',
				'controller' => 'pages',
				'action' => 'view',
			)
		);
		$router->addRoute('pages', $route);

		$route = new Zend_Controller_Router_Route(
			'/contacto',
			array(
				'module' => 'default',
				'controller' => 'contact',
				'action' => 'index',
			)
		);
		$router->addRoute('contact', $route);

		// Clientes
		$route = new Zend_Controller_Router_Route_Regex(
			'clientes/([-a-z]+)',
			array(
				'module' => 'default',
				'controller' => 'pages',
				'action' => 'view',
			),
			array(
				1 => 'client',
			),
			'clientes/%s'
		);
		$router->addRoute('clients', $route);

		// Servicios
		$route = new Zend_Controller_Router_Route_Regex(
			'servicios/([-a-z]+)',
			array(
				'module' => 'default',
				'controller' => 'pages',
				'action' => 'view',
			),
			array(
				1 => 'service',
			),
			'servicios/%s'
		);
		$router->addRoute('services', $route);

		// Projects
		$route = new Zend_Controller_Router_Route_Regex(
			'projects/([-a-z]+)',
			array(
				'module' => 'default',
				'controller' => 'pages',
				'action' => 'view',
			),
			array(
				1 => 'project',
			),
			'proyectos/%s'
		);
		$router->addRoute('projects', $route);

		$now = Zend_Date::now();

		$route = new Zend_Controller_Router_Route(
			'blog/:category_alias/:year/:month/:day/:alias',
			array(
				'module' => 'blog',
				'controller' => 'posts',
				'action' => 'view',
				'category_alias' => null,
				'alias' => null,
			)
		);
		$router->addRoute('blog-post', $route);

		$route = new Zend_Controller_Router_Route(
			'blog/autor/:username',
			array(
				'module' => 'blog',
				'controller' => 'posts',
				'action' => 'author',
				'username' => null,
			)
		);
		$router->addRoute('blog-author', $route);

		$route = new Zend_Controller_Router_Route(
			'blog/:category_alias',
			array(
				'module' => 'blog',
				'controller' => 'posts',
				'action' => 'category',
				'category_alias' => null,
			)
		);
		$router->addRoute('blog-category', $route);

		// Blog
		$route = new Zend_Controller_Router_Route(
			'/blog/:year/:month/:day',
			array(
				'module' => 'blog',
				'controller' => 'posts',
				'action' => 'list',
				'year' => $now->toString('YYYY'),
				'month' => $now->toString('MM'),
				'day' => $now->toString('dd'),
			),
			array(
				'year' => '\d{4}',
				'month' => '\d{2}',
				'day' => '\d{2}',
			)
		);
		$router->addRoute('blog', $route);

		// Sitemap
		$route = new Zend_Controller_Router_Route(
			'/sitemap.xml',
			array(
				'module' => 'default',
				'controller' => 'index',
				'action' => 'sitemap',
			)
		);
		$router->addRoute('sitemap', $route);

		// Local marketing
		$route = new Zend_Controller_Router_Route(
			'/local-marketing',
			array(
				'module' => 'default',
				'controller' => 'localmarketing',
				'action' => 'index',
			)
		);
		$router->addRoute('local-marketing', $route);

		// Local marketing pages
		$route = new Zend_Controller_Router_Route_Regex(
			'local-marketing/([-a-z0-9]+)',
			array(
				'module' => 'default',
				'controller' => 'localmarketing',
				'action' => 'view',
			),
			array(
				1 => 'page',
			),
			'local-marketing/%s'
		);
		$router->addRoute('local-marketing-pages', $route);

		$router->removeDefaultRoutes();
	}

	protected function _initView() {
		$view = new Shark_View();
		$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
		$viewRenderer->setView($view);
		Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
		return $view;
	}

	protected function _initControllerPlugins() {
		$front = Zend_Controller_Front::getInstance();
		$front->registerPlugin(new Shark_Controller_Plugin_Locale());
	}

	protected function _initTest()
	{
		$front = Zend_Controller_Front::getInstance();
	}
}
// @codingStandardsIgnoreEnd