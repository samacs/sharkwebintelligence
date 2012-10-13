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
 * @category   Library
 * @package    Shark
 * @subpackage Controller.Actions
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
/**
 * Shark Action.
 *
 * @category   Library
 * @package    Shark
 * @subpackage Controller.Actions
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
class Shark_Controller_Action_Abstract extends Zend_Controller_Action
{
    /**
     * @var array $_resourceTypes Valid resource types.
     */
    private $_resourceTypes = array(
        'Model',
        'Form',
        'Service',
    );

    const RESOURCE_MODEL = 'Model';

    const RESOURCE_FORM = 'Form';

    const RESOURCE_SERVICE = 'Service';

    /**
     * @var string|Shark_Model_Abstract $model Controller model (used for scaffolding).
     */
    protected $model = null;

    protected $module = null;

    protected $user = null;

    /**
     * Initialize needed variables.
     *
     * @return void
     */
    public function init()
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $this->user = unserialize($auth->getIdentity());
        }
        $this->assign(array('user' => $this->user));
    }

    /**
     * @var array $resources Resource instances.
     */
    protected $resources = array();

    /**
     * Assigns data to the view.
     *
     * @param string|array $spec  The assignment strategy to use.
     * @param mixed        $value If assigning a named variable, use this as the value.
     *
     * @return Zend_View_Abstract Fluent interface.
     * @throws Zend_View_Exception if $spec is neither a string nor an array,
     * or if an attempt to set a private or protected member is detected
     */
    public function assign($spec, $value = null)
    {
        return $this->view->assign($spec, $value);
    }

    /**
     * Gets a model instance.
     *
     * @param string $name   Model name.
     * @param string $module The model's module. Default to the current request module.
     *
     * @return Shark_Model_Abstract Model instance.
     * @throws Shark_Model_Exception If not model class found.
     */
    public function getModel($name = null, $module = null)
    {
        if (null === $name) {
            if (null !== $this->model && !empty($this->model)) {
                $name = $this->model;
            } else {
                throw new Shark_Exception("Model not specified.");
            }
        }
        if ($module === null) {
            if (null !== $this->module && !empty($this->module)) {
                $this->module = $module;
            } else {
                $this->module = $this->getRequest()->getModuleName();
            }
        }
        return $this->_getResource(self::RESOURCE_MODEL, $name, $module);
    }

    /**
     * Gets a form instance.
     *
     * @param string $name   Form name.
     * @param string $module The form's module. Default to the current request module.
     *
     * @return Shark_Form_Abstract Form instance.
     * @throws Shark_Form_Exception If not form class found.
     */
    public function getForm($name, $module = null)
    {
        return $this->_getResource(self::RESOURCE_FORM, $name, $module);
    }

    /**
     * Gets a module resource.
     *
     * @param string $type   Resource type.
     * @param string $name   Resource name.
     * @param string $module The resources's module. Default to the current request module.
     *
     * @return mixed The resource instance.
     * @throws Shark_Exception If the resource class is not found.
     */
    private function _getResource($type, $name, $module)
    {
        if (!in_array($type, $this->_resourceTypes)) {
            throw new Shark_Exception("Resource $type is not a valid resource type.");
        }
        $request = $this->getRequest();
        if (null === $module) {
            $module = strtolower($request->getModuleName());
        }
        $name = strtolower($name);
        $module = ucfirst($module);
        $class = join(
            '_',
            array(
                $module,
                $type,
                $this->_getInflected($name),
            )
        );
        if (!isset($this->resources[strtolower($type)][$class])) {
            if (class_exists($class)) {
                $this->resources[$type][$class] = new $class();
            } else {
                switch ($type) {
                case self::RESOURCE_MODEL:
                    throw new Shark_Model_Exception("Model $class not found.");
                    break;
                case self::RESOURCE_FORM:
                    throw new Shark_Form_Exception("Form $class not found.");
                    break;
                case self::RESOURCE_SERVICE:
                    throw new Shark_Service_Exception("Service $class not found.");
                    break;
                default:
                    throw new Shark_Exception("$type $class not found.");
                    break;
                }
            }
        }
        return $this->resources[$type][$class];
    }

    /**
     * Gets the given name converted from CamelCase to underscore.
     *
     * @param string $name Name to convert.
     *
     * @return string Converted name.
     */
    private function _getInflected($name)
    {
        $inflector = new Zend_Filter_Inflector(':class');
        $inflector->setRules(
            array(
                ':class' => array(
                    'Word_CamelCaseToUnderscore',
                ),
            )
        );
        return ucfirst(
            $inflector->filter(
                array(
                    'class' => $name,
                )
            )
        );
    }

    /**
     * Go to route proxy.
     *
     * @param string $routeName Route name.
     * @param array  $options   Route options.
     *
     * @return void
     */
    protected function gotoRoute($routeName, $options = array())
    {
        return $this->_helper->redirector->gotoRoute($options, $routeName);
    }
}