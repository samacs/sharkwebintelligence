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
 * @category  Application
 * @package   Entities
 * @author    Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright 2012 Shark Web Intelligence
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version   ${CURRENT_VERSION}
 * @link      http://www.sharkwebintelligence.com
 */
/**
 * Blog post entity.
 *
 * @category  Application
 * @package   Entities
 * @author    Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright 2012 Shark Web Intelligence
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version   ${CURRENT_VERSION}
 * @link      http://www.sharkwebintelligence.com
 */
class Entity_Post extends Zend_Db_Table_Row_Abstract
{
    protected $table;

    /**
     * Gets the post author.
     *
     * @return Entity_Author
     */
    public function getAuthor()
    {
        return $this->findParentRow('Table_Users');
    }

    /**
     * Gets the post category.
     *
     * @return Entity_Category
     */
    public function getCategory()
    {
        return $this->findParentRow('Table_Categories');
    }

    /**
     * Magic method to get related tables.
     *
     * @param string $name Method name.
     *
     * @return mixed
     */
    public function __get($name)
    {
        $method = 'get' . ucfirst($name);
        if (in_array($method, get_class_methods($this))) {
            return $this->$method();
        } else {
            return parent::__get($name);
        }
    }
}