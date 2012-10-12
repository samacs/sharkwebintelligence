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
 * @subpackage Service
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
/**
 * Abstract service class.
 *
 * @category   Library
 * @package    Shark
 * @subpackage Service
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
abstract class Shark_Service_Abstract
{
    /**
     * Constructor.
     *
     * @param mixed $options Object options.
     *
     * @return void
     */
    public function __construct($options)
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }
        if (is_array($options)) {
            $this->setOptions($options);
        }
        $this->init();
    }

    /**
     * Constructor extensions.
     *
     * @return void
     */
    public function init()
    {
    }

    /**
     * Sets object options.
     *
     * @param array $options Object options.
     *
     * @return Shark_Service_Abstract
     */
    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $name => $value) {
            $method = 'set' . ucfirst($name);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    /**
     * Magic method to set object properties.
     *
     * @param string $name  Property name
     * @param mixed  $value Property value.
     *
     * @return mixed|Shark_Service_Abstract
     */
    public function __set($name, $value)
    {
        $method = 'set' . ucfirst($name);
        if (in_array($method, get_class_methods($this))) {
            return $this->$method($value);
        }
    }

    /**
     * Magic method to get object properties.
     *
     * @param string $name Property name.
     *
     * @return mixed
     */
    public function __get($name)
    {
        $method = 'get' . ucfirst($name);
        if (in_array($method, get_class_methods($this))) {
            return $this->$method();
        }
    }
}