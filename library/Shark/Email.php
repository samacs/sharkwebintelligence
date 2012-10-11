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
 * @category  Library
 * @package   Shark
 * @author    Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright 2012 Shark Web Intelligence
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version   ${CURRENT_VERSION}
 * @link      http://www.sharkwebintelligence.com
 */
/**
 * Email sender.
 *
 * @category  Library
 * @package   Shark
 * @author    Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright 2012 Shark Web Intelligence
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version   ${CURRENT_VERSION}
 * @link      http://www.sharkwebintelligence.com
 */
class Shark_Email
{
    /**
     * @var Zend_Mail $_email Mailer.
     */
    private $_email;

    /**
     * @var Zend_View $view View object.
     */
    protected $view;

    /**
     * @var string $_from From email address.
     */
    private $_from;

    /**
     * @var string $_to To email address.
     */
    private $_to;

    /**
     * Constructor.
     *
     * Set default properties.
     *
     * @param string $toEmail   To email address.
     * @param string $toName    To name.
     * @param string $subject   Email subject.
     * @param string $fromEmail From email address.
     * @param string $fromName  From name.
     *
     * @return void.
     */
    public function __construct($toEmail, $toName, $subject, $fromEmail = null, $fromName = null)
    {
        $this->_email = new Zend_Mail('UTF-8');
        $this->_email->addTo($toName, $toEmail);
        $this->_email->setSubject($subject);
        if ($fromEmail !== null && $fromName !== null) {
            $this->_email->setFrom($fromName, $fromEmail);
        } else {
            $config = Shark_Config::getConfig();
            $this->_email->setFrom($config->site->title, $config->site->title);
        }
        $this->view = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->view;
        $this->init();
    }

    /**
     * Constructor extension.
     *
     * @return void
     */
    public function init()
    {
    }

    /**
     * Sends an email address using the specified template.
     *
     * @param string $template Template to use.
     * @param array  $data     Data to use in template.
     *
     * @return boolean True if send successful.
     */
    public function send($template, $data = array())
    {
        $template = 'email' . DS . $template . '.phtml';
        $body = $this->view->partial($template, $data);
        $this->_email->setBodyHtml($body);
        return $this->_email->send();
    }
}