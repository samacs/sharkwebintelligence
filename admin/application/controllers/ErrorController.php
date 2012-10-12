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
 * Admin error controller
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
class Admin_ErrorController extends Shark_Controller_Action
{
    /**
     * Error action.
     *
     * @return void
     */
    public function errorAction()
    {
        $request = $this->getRequest();
        $error = $request->getParam('error_handler');
        if (!$error instanceof ArrayObject) {
            return $this->_redirect('/');
        }
        $type = $error->type;
        switch ($type) {
        case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER:
        case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
        case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
        case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
            $message = 'SHARK_ERROR_NOT_FOUND';
            $code = 404;
            break;
        case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER:
        default:
            $message = 'SHARK_ERROR_APPLICATION_ERROR';
            $code = 500;
            break;
        }
        $this->getResponse()->setHttpResponseCode($code);
        $this->assign(
            array(
                'error' => $error,
                'exception' => $error->exception,
                'params' => $request->getParams(),
                'message' => $message,
            )
        );
    }
}