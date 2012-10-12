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
 * @category   Library
 * @package    Shark
 * @subpackage View
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
/**
 * Admin view.
 *
 * @category   Library
 * @package    Shark
 * @subpackage View
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
class Shark_View_Admin extends Zend_View
{
    /**
     * Initialize view.
     *
     * @return void
     */
    public function init()
    {
        $config = Shark_Config::getConfig();

        $this->doctype('HTML5');

        $this->headTitle($config->site->title)->setSeparator(' - ');
        $this->headTitle('Administración');

        $this->addHelperPath(
            'Shark/View/Helper',
            'Shark_View_Helper'
        );

        $this->headLink()->appendStylesheet($this->baseUrl('/css/styles.css'));

        $this->inlineScript()->appendFile('//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js');
        $this->inlineScript()->appendScript(
            'window.jQuery || document.write(\'<script src="' . $this->baseUrl('/js/jquery.min.js') . '"><\/script>\');'
        );
        $this->inlineScript()->appendFile($this->baseUrl('/js/bootstrap.min.js'));

        $this->inlineScript()->appendFile($this->baseUrl('/js/plugins.js'));
        $this->inlineScript()->appendFile($this->baseUrl('/js/application.js'));

        $this->assign($config->site->toArray());

        Zend_Layout::startMvc();
    }
}