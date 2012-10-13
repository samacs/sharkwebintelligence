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
 * User form.
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
class Admin_Form_User extends Shark_Form
{
    /**
     * Initialize form.
     *
     * @return void
     */
    public function init()
    {
        $this->setName('form-user');
        $this->setMethod('post');

        $this->addElementPrefixPath(
            'Admin_Validate',
            APPLICATION_PATH . DS . 'validators',
            'validate'
        );

        $this->addElement(
            'text',
            'name',
            array(
                'label' => 'USERS_FORM_NAME_LABEL',
                'required' => true,
                'filters' => array(
                    'StripTags',
                    'StringTrim',
                ),
            )
        );

        $this->addElement(
            'text',
            'email',
            array(
                'label' => 'USERS_FORM_EMAIL_LABEL',
                'required' => true,
                'filters' => array(
                    'StripTags',
                    'StringTrim',
                    'StringToLower',
                ),
                'validators' => array(
                    'EmailAddress',
                ),
            )
        );

        $this->addElement(
            'text',
            'username',
            array(
                'label' => 'USERS_FORM_USERNAME_LABEL',
                'required' => true,
                'filters' => array(
                    'StripTags',
                    'StringTrim',
                    'StringToLower',
                ),
            )
        );

        $this->addElement(
            'password',
            'password',
            array(
                'label' => 'USERS_FORM_PASSWORD_LABEL',
                'required' => true,
                'filters' => array(
                    'StripTags',
                    'StringTrim',
                ),
                'validators' => array(
                    'PasswordConfirm',
                ),
            )
        );

        $this->addElement(
            'password',
            'password_confirm',
            array(
                'label' => 'USERS_FORM_PASSWORD_CONFIRM_LABEL',
                'required' => false,
                'filters' => array(
                    'StripTags',
                    'StringTrim',
                ),
            )
        );

        $this->addElement(
            'ckeditor',
            'bio',
            array(
                'label' => 'SHARK_FORM_BIO_LABEL',
                'required' => false,
                'filters' => array(
                    'StringTrim',
                ),
            )
        );

        $this->addElement(
            'text',
            'gplus_profile',
            array(
                'label' => 'SHARK_FORM_GPLUS_PROFILE_LABEL',
                'required' => true,
                'filters' => array(
                    'StringTrim',
                    'StripTags',
                ),
                'validators' => array(
                    'NotEmpty',
                    'Uri',
                ),
            )
        );

        $this->addElement(
            'text',
            'twitter_profile',
            array(
                'label' => 'SHARK_FORM_TWITTER_PROFILE_LABEL',
                'required' => true,
                'filters' => array(
                    'StringTrim',
                    'StripTags',
                ),
                'validators' => array(
                    'NotEmpty',
                    'Uri',
                ),
            )
        );

        $this->addElement(
            'text',
            'facebook_profile',
            array(
                'label' => 'SHARK_FORM_FACEBOOK_PROFILE_LABEL',
                'required' => true,
                'filters' => array(
                    'StringTrim',
                    'StripTags',
                ),
                'validators' => array(
                    'NotEmpty',
                    'Uri',
                ),
            )
        );

        $this->addElement(
            'hash',
            'csrf',
            array(
                'salt' => 'unique',
            )
        );

    }
}