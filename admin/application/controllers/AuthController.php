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
 * Admin authentication controller.
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
class Admin_AuthController extends Shark_Controller_Action
{
    /**
     * Login action.
     *
     * @return void
     */
    public function loginAction()
    {
        $request = $this->getRequest();
        $form = new Admin_Form_User_Login();
        $message = null;
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($form->isValid($data)) {
                $service = new Admin_Service_Auth(
                    array(
                        'identity' => $data['username'],
                        'credentials' => $data['password'],
                    )
                );
                $result = $service->authenticate();
                switch ($result->getCode()) {
                case Zend_Auth_Result::SUCCESS:
                    return $this->_redirect('/');
                    break;
                case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
                    $message = 'Usuario no encontrado';
                    break;
                case Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS:
                    $message = 'Usuario ambiguo';
                    break;
                case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                    $message = 'Contraseña incorrecta';
                    break;
                case Zend_Auth_Result::FAILURE_UNCATEGORIZED:
                default:
                    $message = 'Error desconocido';
                    break;
                }
                var_dump($result);
            }
        }
        $this->view->assign(
            array(
                'form' => $form,
                'message' => $message,
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