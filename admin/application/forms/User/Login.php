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
 * @subpackage Forms
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
/**
 * Login form.
 *
 * @category   Shark
 * @package    Admin
 * @subpackage Forms
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
class Admin_Form_User_Login extends Admin_Form_User
{
    /**
     * Initialize form.
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        $this->setName('form-user-login');

        $removedElements = array(
            'name',
            'email',
            'bio',
            'password_confirm',
            'gplus_profile',
            'twitter_profile',
            'facebook_profile',
        );

        foreach ($removedElements as $element) {
            $this->removeElement($element);
        }

        $this->getElement('password')->removeValidator('PasswordConfirm');

        $this->addElement(
            'button',
            'login',
            array(
                'label' => 'Login',
                'ignore' => true,
                'type' => 'submit',
                'class' => 'btn btn-success btn-large',
            )
        );
    }

    /**
     * Logout action.
     *
     * @return void
     */
    public function logoutAction()
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $auth->clearIdentity();
        }
        return $this->_redirect('/');
    }
}
