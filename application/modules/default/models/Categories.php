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
 * @category   Application
 * @package    Models
 * @subpackage Categories
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
/**
 * Blog categories model.
 *
 * @category   Application
 * @package    Models
 * @subpackage Categories
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
class Model_Categories
{
    /**
     * @var Table_Categories $table Categories table.
     */
    protected $table;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->table = new Table_Categories();
    }

    /**
     * Get category by alias.
     *
     * @param string $alias Category alias.
     *
     * @return Entity_Category
     */
    public function getByAlias($alias)
    {
        $select = $this->table->select();
        $select->where('alias = ?', $alias);
        return $this->table->fetchRow($select);
    }

    /**
     * Gets all categories.
     *
     * @return Zend_Db_Table_Rowset
     */
    public function findAll()
    {
        $select = $this->table->select();
        $select->where('is_active = ?', 1);
        $select->order('title DESC');
        return $this->table->fetchAll($select);
    }
}