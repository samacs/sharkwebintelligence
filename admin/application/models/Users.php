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
 * @category   Shark
 * @package    Admin
 * @subpackage Models
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
class Admin_Model_Users extends Shark_Model_Abstract
{
    /**
     * Find user by his username.
     *
     * @param string $username Username.
     *
     * @return Admin_Entity_User
     */
    public function findByUsername($username)
    {
        $select = $this->select();
        $select->where('username = ?', $username);
        return $this->fetchRow($select);
    }

    /**
     * Find user by his email.
     *
     * @param string $email User email.
     *
     * @return Admin_Entity_User
     */
    public function findByEmail($email)
    {
        $select = $this->select();
        $select->where('email = ?', $email);
        return $this->fetchRow($select);
    }
}