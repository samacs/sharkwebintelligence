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
 * @subpackage Controllers
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
/**
 * Users controller.
 *
 * @category   Shark
 * @package    Admin
 * @subpackage Controllers
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
class Admin_UsersController extends Shark_Controller_Action
{
    /**
     * Account action.
     *
     * @return void
     */
    public function accountAction()
    {
        $request = $this->getRequest();
        $form = new Admin_Form_User();
        $form->getElement('password')->setRequired(false);
        $form->getElement('username')->setAttrib('readonly', 'readonly');
        $errors = array();
        $message = null;
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($form->isValid($data)) {
                $model = new Admin_Model_Users();
                $user = $model->find($this->user->id)->current();
                $user->setFromArray($data);
                if (!$user->save()) {
                    $message = 'SHARK_ERROR_ROW_NOT_SAVED';
                }
            } else {
                foreach ($form->getErrors() as $index => $messages) {
                    if (!empty($messages)) {
                        $element = $form->getElement($index);
                        $error = new stdClass();
                        $error->field = $element->getLabel();
                        $error->message = implode('<br />', $element->getMessages());
                        $errors[] = $error;
                    }
                }
                $this->view->errors = $errors;
            }
        } else {
            $form->populate($this->user->toArray());
        }
        $form->setDecorators(
            array(
                array(
                    'ViewScript',
                    array(
                        'viewScript' => 'users/_form.phtml',
                        'form' => $form,
                        'errors' => $errors,
                        'message' => $message,
                    ),
                ),
            )
        );
        $this->assign(array('user' => $this->user, 'form' => $form));
    }
}