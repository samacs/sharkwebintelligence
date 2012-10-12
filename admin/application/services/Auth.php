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
 * @subpackage Services
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
/**
 * Authentication service.
 *
 * @category   Shark
 * @package    Admin
 * @subpackage Services
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
class Admin_Service_Auth extends Shark_Service_Abstract
{

    /**
     * @var string $identity User identity.
     */
    protected $identity;

    /**
     * @var string $credential User credentials.
     */
    protected $credential;

    /**
     * @var Zend_Auth_Adapter_DbTable $_adapter Zend_Auth adapter.
     */
    private $_adapter;

    /**
     * @var Zend_Auth $_auth Zend_Auth object.
     */
    private $_auth;

    /**
     * Sets user identity.
     *
     * @param string $identity User's identity.
     *
     * @return Admin_Service_Auth
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
        return $this;
    }

    /**
     * Sets the user credentials.
     *
     * @param string $credentials User's credentials.
     *
     * @return Shark_Service_Auth
     */
    public function setCredentials($credentials)
    {
        $this->credentials = $credentials;
        return $this;
    }

    /**
     * Gets the user identity.
     *
     * @return mixed
     */
    public function getIdentity()
    {
        if (null === $this->_auth) {
            $this->_auth = Zend_Auth::getInstance();
        }
        return $this->_auth->getIdentity();
    }

    /**
     * Initialize authentication.
     *
     * @return void
     */
    public function init()
    {
        $this->_auth = Zend_Auth::getInstance();
        $this->_adapter = new Zend_Auth_Adapter_DbTable(
            Zend_Db_Table_Abstract::getDefaultAdapter()
        );
        $this->_adapter->setCredentialColumn('password');
        $this->_adapter->setTableName('core_users');
        $this->_adapter->setCredentialTreatment('SHA1(CONCAT(?, salt))');
    }


    /**
     * Authenticates the user.
     *
     * @return Zend_Result
     */
    public function authenticate()
    {
        $this->_adapter->setIdentityColumn(stripos($this->identity, '@') ? 'email' : 'username');
        $this->_adapter->setIdentity($this->identity);
        $this->_adapter->setCredential($this->credentials);
        $result = $this->_auth->authenticate($this->_adapter);
        if ($result->isValid()) {
            $storage = $this->_auth->getStorage();
            $user = $this->_adapter->getResultRowObject(null, array('password', 'salt'));
            $storage->write($user);
            $session = new Zend_Session_Namespace('Zend_Auth');
            $session->setExpirationSeconds(3600);
            $session->user = $user;
        }
        return $result;
    }
}