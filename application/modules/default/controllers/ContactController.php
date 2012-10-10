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
 * @package   Controllers
 * @author    Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright 2012 Shark Web Intelligence
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version   ${CURRENT_VERSION}
 * @link      http://www.sharkwebintelligence.com
 */
/**
 * Contact form controller
 *
 * @category  Application
 * @package   Controllers
 * @author    Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright 2012 Shark Web Intelligence
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version   ${CURRENT_VERSION}
 * @link      http://www.sharkwebintelligence.com
 */
class ContactController extends Shark_Controller_Action
{
    // @codingStandardsIgnoreStart
    public function init()
    {

    }
    // @codingStandardsIgnoreEnd

    /**
     * Index action.
     *
     * @return void
     */
    public function indexAction()
    {
        $request = $this->getRequest();
        $form = new Form_Contact();
        $form->setAction($this->view->url(array(), 'contact'));
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($form->isValid($data)) {
                $config = Shark_Config::getConfig();
                $data = (object)$data;
                $subject = $config->site->title . ' - ATENCIÓN - ' . $data->subject;
                $notificationEmail = new Shark_Email($config->site->title, $config->site->email, $subject);
                $notificationEmail->send('contact/notification', array('contact' => $data));
                $subject = $config->site->title . ' - Contacto';
                $thanksEmail = new Shark_Email($data->name, $data->email, $subject);
                if ($thanksEmail->send('contact/thanks', array('contact' => $data))) {
                    $newsletter = new Shark_Newsletter($config->site->newsletter->username, $config->site->newsletter->usertoken);
                    $newsletter->suscribe(1, $data->name, $data->email);
                    return $this->_redirect($this->view->url(array('page' => 'gracias'), 'pages'));
                }
            }
        }
        $this->view->assign(
            array(
                'form' => $form,
            )
        );
    }
}