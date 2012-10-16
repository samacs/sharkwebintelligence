<?php
/**
 * Shark Framework
 *
 * LICENSE
 *
 * Copyright  Shark Web Intelligence
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @category   Shark
 * @package    Admin
 * @subpackage Bootstrap
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
/**
 * Admin bootstrap.
 *
 * @category   Shark
 * @package    Admin
 * @subpackage Bootstrap
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    /**
     * Initialize session.
     *
     * @return void
     */
    // @codingStandardsIgnoreStart
    protected function _initSession()
    {
        Zend_Session::start();
    }
    // @codingStandardsIgnoreEnd

    /**
     * Initialize autoloader and resources.
     *
     * @return void
     */
    // @codingStandardsIgnoreStart
    protected function _initAutoloader()
    {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        new Zend_Loader_Autoloader_Resource(
            array(
                'basePath' => APPLICATION_PATH,
                'namespace' => 'Admin',
                'resourceTypes' => array(
                    'model' => array(
                        'path' => 'models',
                        'namespace' => 'Model',
                    ),
                    'form' => array(
                        'path' => 'forms',
                        'namespace' => 'Form',
                    ),
                    'service' => array(
                        'path' => 'services',
                        'namespace' => 'Service',
                    ),
                    'entity' => array(
                        'path' => 'entities',
                        'namespace' => 'Entity',
                    ),
                    'table' => array(
                        'path' => 'tables',
                        'namespace' => 'Table',
                    ),
                ),
            )
        );
        return $autoloader;
    }
    // @codingStandardsIgnoreEnd

    /**
     * Initialize routes.
     *
     * @return void
     */
    // @codingStandardsIgnoreStart
    protected function _initRoutes()
    {
        $this->bootstrap('frontController');
        $frontController = $this->getResource('frontController');
        $router = new Zend_Controller_Router_Rewrite();
        $path = APPLICATION_PATH . DS . 'configs' . DS . 'routes';
        $iterator = new DirectoryIterator($path);
        $files = array();
        foreach ($iterator as $file) {
            if ($file->isDir() || $file->isDot()) {
                continue;
            }
            $filename = $path . DS . $file->getFilename();
            if (preg_match('/^.*\.ini$/i', $filename)) {
                $files[] = $filename;
            }
        }
        foreach ($files as $filename) {
            $config = new Zend_Config_Ini($filename, 'routes');
            $router->addConfig($config, 'routes');
        }
        $router->removeDefaultRoutes();
        $frontController->setRouter($router);
    }
    // @codingStandardsIgnoreEnd

    /**
     * Initialize date.
     *
     * @return void
     */
    // @codingStandardsIgnoreStart
    protected function _initDate()
    {
        Zend_Date::setOptions(
            array(
                'format_type' => 'php',
            )
        );
    }
    // @codingStandardsIgnoreEnd

    /**
     * Initialize plugins.
     *
     * @return void
     */
    // @codingStandardsIgnoreStart
    protected function _initPlugins()
    {
        $frontController = $this->getResource('frontController');
        $frontController->registerPlugin(new Shark_Controller_Plugin_Admin());
        $frontController->registerPlugin(
            new Zend_Controller_Plugin_ErrorHandler(
                array(
                    'module' => 'Admin',
                    'controller' => 'error',
                    'action' => 'error',
                )
            )
        );
    }
    // @codingStandardsIgnoreEnd

    /**
     * Initialize translation.
     *
     * @return void
     */
    // @codingStandardsIgnoreStart
    protected function _initTranslate()
    {
        $site = Shark_Config::getConfig()->site;
        $locale = new Zend_Locale($site->locale);
        $translate = new Zend_Translate(
            array(
                'adapter' => 'ini',
                'content' => APPLICATION_PATH . DS . 'languages',
                'locale' => $locale,
                'scan' => Zend_Translate::LOCALE_DIRECTORY,
            )
        );
        Zend_Registry::set('Zend_Locale', $locale);
        Zend_Registry::set('Zend_Translate', $translate);
    }
    // @codingStandardsIgnoreEnd

    /**
     * Initialize view.
     *
     * @return void
     */
    // @codingStandardsIgnoreStart
    protected function _initView()
    {
        $view = new Shark_View_Admin();
        Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setView($view);
        return $view;
    }
    // @codingStandardsIgnoreEnd
}