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
 * @subpackage Tables
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
/**
 * Users table.
 *
 * @category   Shark
 * @package    Admin
 * @subpackage Tables
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
class Admin_Table_Users extends Shark_Table_Abstract
{
    // @codingStandardsIgnoreStart
    protected $_name = 'core_users';
    // @codingStandardsIgnoreEnd

    // @codingStandardsIgnoreStart
    protected $_dependentTables = array(
        'Admin_Table_Posts',
    );
    // @codingStandardsIgnoreEnd

    /**
     * Salt user password.
     *
     * @param array $data Column-value pairs.
     *
     * @return mixed The primary key for the inserted row.
     */
    public function insert(array $data)
    {
        $this->_saltPassword($data);
        return parent::insert($data);
    }

    /**
     * Updates the password salt if required.
     *
     * @param array        $data  Column-value pairs.
     * @param array|string $where An SQL WHERE clause, or an array of SQL WHERE clauses.
     *
     * @return int The number of rows updated.
     */
    public function update(array $data, $where)
    {
        if (array_key_exists('password', $data) && '' !== $data['password']) {
            $this->_saltPassword($data);
        } else {
            unset($data['password']);
        }
        return parent::update($data, $where);
    }

    /**
     * Salt user password.
     *
     * @param array &$data Row data.
     *
     * @return void
     */
    private function _saltPassword(&$data)
    {
        $data['salt'] = md5($this->_salt());
        $data['password'] = sha1($data['password'] . $data['salt']);
    }

    /**
     * Creates a salt string.
     *
     * @return string
     */
    private function _salt()
    {
        $salt = '';
        for ($i = 0; $i < 50; $i++) {
            $salt .= chr(rand(33, 126));
        }
        return $salt;
    }
}