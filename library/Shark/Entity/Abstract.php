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
 * @subpackage Entity
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
/**
 * Abstract Entity class.
 *
 * @category   Library
 * @package    Shark
 * @subpackage Entity
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
class Shark_Entity_Abstract extends Shark_Table_Row_Abstract
{

    /**
     * Gets a property by its getter.
     *
     * @param string $name Property name.
     *
     * @return mixed The property value.
     */
    public function __get($name)
    {
        if (!isset($this->$name)) {
            $method = 'get' . ucfirst($name);
            $methods = get_class_methods($this);
            if (in_array($method, $methods)) {
                return $this->$method();
            }
        } else if (isset($this->_data[$name])) {
            return $this->_data[$name];
        }
    }
}