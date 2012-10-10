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
 * @package   Forms
 * @author    Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright 2012 Shark Web Intelligence
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version   ${CURRENT_VERSION}
 * @link      http://www.sharkwebintelligence.com
 */
/**
 * Contact form.
 *
 * @category  Application
 * @package   Forms
 * @author    Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright 2012 Shark Web Intelligence
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version   ${CURRENT_VERSION}
 * @link      http://www.sharkwebintelligence.com
 */
class Form_Contact extends Zend_Form
{
    /**
     * Contact form.
     *
     * @return void
     */
    public function init()
    {
        $this->setMethod('post');

        $this->setAttribs(
            array(
                'id' => 'form-contact',
                'name' => 'form-contact',
                'class' => 'form',
            )
        );

        $this->setDecorators(
            array(
                'FormElements',
                array(
                    'HtmlTag',
                    array(
                        'tag' => 'div',
                        'class' => 'form-container',
                    ),
                ),
                'Form',
            )
        );

        $this->setElementDecorators(
            array(
                'Label',
                'ViewHelper',
                array('Errors', array('escape' => false)),
                array(
                    'HtmlTag',
                    array(
                        'tag' => 'div',
                        'class' => 'controls',
                    ),
                ),
            )
        );

        // Name
        $this->addElement(
            'text',
            'name',
            array(
                'label' => 'Nombre:',
                'required' => true,
                'filters' => array(
                    'StripTags',
                    'StringTrim',
                ),
                'errorMessages' => array(
                    'isEmpty' => 'Este campo no puede quedar en blanco.',
                ),
            )
        );

        // Email
        $this->addElement(
            'text',
            'email',
            array(
                'label' => 'Email:',
                'required' => true,
                'filters' => array(
                    'StripTags',
                    'StringTrim',
                    'StringToLower',
                ),
                'validators' => array(
                    array(
                        'EmailAddress',
                        false,
                        array(
                            'messages' => array(
                                'emailAddressInvalidFormat' => '<u><strong>%value%</strong></u> no es una dirección de correo electrónico válida.',
                                'emailAddressInvalidHostname' => '<u><strong>%hostname%</strong></u> no es un nombre de dominio válido para la dirección %value%.',
                                'hostnameInvalidHostname' => '<u><strong>%value%</strong></u> no concuerda con la estructura esperada para un dominio DNS.',
                                'hostnameLocalNameNotAllowed' => '<u><strong>%value%</strong></u> parece ser un nombre de red local pero no están permitidas redes locales.',
                            ),
                        ),
                    ),
                ),
            )
        );

        // Asunto
        $this->addElement(
            'text',
            'subject',
            array(
                'label' => 'Asunto:',
                'required' => true,
                'filters' => array(
                    'StripTags',
                    'StringTrim',
                ),
            )
        );

        // Mensaje
        $this->addElement(
            'textarea',
            'message',
            array(
                'label' => 'Mensaje:',
                'required' => true,
                'filters' => array(
                    'StringTrim',
                    'StripTags',
                ),
                'rows' => 3,
                'cols' => 25,
            )
        );

        $this->addElement(
            'button',
            'submit',
            array(
                'label' => 'Enviar',
                'type' => 'submit',
                'ignore' => true,
                'class' => 'btn btn-primary btn-large',
            )
        )->submit->removeDecorator('Label');
    }
}