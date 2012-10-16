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
 * @category   Site
 * @package    Default
 * @subpackage Controllers
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
/**
 * Index controller.
 *
 * @category   Site
 * @package    Default
 * @subpackage Controllers
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
class IndexController extends Shark_Controller_Action
{
    /**
     * Frontpage controller.
     *
     * @return void
     */
    public function indexAction()
    {
        $slides = array();
        $slide = new stdClass();
        $slide->order = 0;
        $slide->image = $this->view->baseUrl('/img/slides/websites.jpg');
        $slide->title = 'Web Hosting';
        $slide->link = $this->view->url(array('service' => 'hospedaje-web'), 'services');
        $slides[] = $slide;
        $slide = new stdClass();
        $slide->order = 1;
        $slide->image = $this->view->baseUrl('/img/slides/redes-sociales.jpg');
        $slide->title = 'First thumbnail label';
        //$slide->text = 'Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.';
        $slide->link = $this->view->url(array('service' => 'desarrollo-web'), 'services');
        $slides[] = $slide;
        $slide = new stdClass();
        $slide->order = 2;
        $slide->image = $this->view->baseUrl('/img/slides/seo-smm.jpg');
        $slide->title = 'Second Thumbnail label';
        //$slide->text = 'Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.';
        $slide->link = $this->view->url(array('service' => 'diseno-web'), 'services');
        $slides[] = $slide;
        $slide = new stdClass();
        $slide->order = 3;
        $slide->image = $this->view->baseUrl('/img/slides/email-marketing.jpg');
        $slide->title = 'Third Thumbnail label';
        //$slide->text = 'Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.';
        $slide->link = $this->view->url(array('service' => 'redes-sociales'), 'services');
        $slides[] = $slide;
        $slide = new stdClass();
        $slide->order = 4;
        $slide->image = $this->view->baseUrl('/img/slides/web-analytics.jpg');
        $slide->title = 'Fourth Thumbnail label';
        //$slide->text = 'Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.';
        $slide->link = $this->view->url(array('service' => 'email-marketing'), 'services');
        $slides[] = $slide;
        $this->view->assign(
            array(
                'slides' => $slides,
            )
        );
    }

    /**
     * Shows the site map.
     *
     * @return string
     */
    public function sitemapAction()
    {
        $this->view->layout()->disableLayout();
        $config = new Zend_Config_Xml(APPLICATION_PATH . DS . 'configs' . DS . 'navigation.xml', 'mainnav');
        $container = new Zend_Navigation($config);
        $this->view->navigation($container);
        $this->_helper->viewRenderer->setNoRender(true);
        $response = $this->getResponse();
        $response->setHeader('Content-Type', 'text/xml');
        echo $this->view->navigation()->sitemap();
    }
}
