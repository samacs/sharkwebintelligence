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

        $this->addElement(
            'text',
            'name',
            array(
                'label' => 'Nombre',
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
                'label' => 'Email',
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
                'label' => 'Username',
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
                'label' => 'Password',
                'required' => true,
                'filters' => array(
                    'StripTags',
                    'StringTrim',
                ),
            )
        );
    }
}