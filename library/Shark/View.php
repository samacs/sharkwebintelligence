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
 * Custom view.
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
class Shark_View extends Zend_View
{

    /**
     * Initialize view.
     *
     * @return void
     */
    public function init()
    {
        $site = Shark_Config::getConfig()->site;

        // Helper paths
        $this->addHelperPath('Shark' . DS . 'View' . DS . 'Helper', 'Shark_View_Helper');
        $this->addScriptPath(APPLICATION_PATH . DS . 'views' . DS . 'scripts');

        // Doctype
        $this->doctype($site->doctype);

        // Meta data
        $this->headMeta()->setCharset($site->charset);

        if (isset($site->meta)) {
            foreach ($site->meta->toArray() as $name => $content) {
                $this->headMeta()->appendName($name, $content);
            }
        }

        // Title
        $this->headTitle($site->title);
        $this->headTitle()->setSeparator(' - ');

        // Links
        if ($site->bootstrap) {
            $this->minifyHeadLink()->appendStylesheet($this->baseUrl('/css/bootstrap.min.css'));
            $this->minifyHeadLink()->appendStylesheet($this->baseUrl('/css/bootstrap-responsive.min.css'));
        }
        $this->minifyHeadLink()->appendStylesheet($this->baseUrl('/js/prettyPhoto/jquery.prettyPhoto.css'));
        $this->minifyHeadLink()->appendStylesheet($this->baseUrl('/skins/' . $site->skin . '/css/screen.css'), array('media' => 'screen, projection'));
        $this->minifyHeadLink(
            array(
                'rel' => 'author',
                'href' => 'https://plus.google.com/103223395393674962309?rel=author',
                'type' => 'text/plain',
            )
        );
        $this->minifyHeadLink(
            array(
                'rel' => 'shortcut icon',
                'href' => $this->baseUrl('/favicon.ico'),
                'type' => 'image/x-icon',
            )
        );

        // JavaScript
        $this->minifyHeadScript()->prependFile($this->baseUrl('/js/modernizr.min.js'));
        $this->minifyHeadScript()->appendFile('http://sharkwebintelligence.com/livehelp/include/javascript.php');
        $this->minifyInlineScript()->prependFile('http://code.jquery.com/jquery-latest.min.js');
        $this->minifyInlineScript()->appendScript('window.jQuery || document.write(\'<script src="' . $this->baseUrl('/js/jquery.min.js') . '"><\/script>\');');
        if ($site->bootstrap) {
            $this->minifyInlineScript()->appendFile($this->baseUrl('/js/bootstrap.min.js'));
        }
        $this->minifyInlineScript()->appendFile($this->baseUrl('/js/prettyPhoto/jquery.prettyPhoto.js'));
        $this->minifyInlineScript()->appendFile($this->baseUrl('/js/application.js'));
        $this->minifyInlineScript()->appendScript(
            'var _gaq=[["_setAccount", "' . getenv('GA') . '"],["_trackPageview"], ["_setDomainName", "' . $this->serverUrl() . '"]];
                (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
                g.src=("https:"==location.protocol?"//ssl":"//www")+".google-analytics.com/ga.js";
                s.parentNode.insertBefore(g,s)}(document,"script"));'
        );

        // Assign variables
        $this->assign($site->toArray());

        // Start layout
        $this->initLayout($site);
    }

    /**
     * Initialize layout.
     *
     * @param Zend_Config $settings Layout settings.
     *
     * @return void
     */
    public function initLayout(Zend_Config $settings = null)
    {
        $layout = Zend_Layout::startMvc(
            array(
                'layout' => 'layout',
                'layoutPath' => APPLICATION_PATH . DS . 'layouts',
            )
        );
        if ($settings) {
            // Set custom values
        }
    }
}