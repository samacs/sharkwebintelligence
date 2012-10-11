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
 * @package    Core
 * @subpackage Models
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
/**
 * Users model.
 *
 * @category   Application
 * @package    Core
 * @subpackage Models
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
class Model_Users
{
    /**
     * @var Table_Users $table Users table.
     */
    protected $table;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->table = new Table_Users();
    }

    /**
     * Get all users.
     *
     * @return Zend_Db_Table_Rowset
     */
    public function findAll()
    {
        $select = $this->table->select();
        $select->where('is_active = ?', 1);
        $select->order('username ASC');
        return $this->table->fetchAll($select);
    }

    /**
     * Get a user by his username.
     *
     * @param string $username Username.
     *
     * @return Entity_User
     */
    public function findByUsername($username)
    {
        $select = $this->table->select();
        $select->where('username = ?', $username);
        return $this->table->fetchRow($select);
    }
}