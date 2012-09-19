<?php

class IndexController extends Shark_Controller_Action {

    public function indexAction() {
    	$slides = array();
        $slide = new stdClass();
        $slide->order = 0;
        $slide->image = $this->view->baseUrl('/img/slides/web-hosting.jpg');
        $slide->title = 'Web Hosting';
        $slide->link = $this->view->url(array('service' => 'hospedaje-web'), 'services');
        $slides[] = $slide;
        $slide = new stdClass();
        $slide->order = 1;
        $slide->image = $this->view->baseUrl('/img/slides/desarrollo-web.jpg');
        $slide->title = 'First thumbnail label';
        //$slide->text = 'Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.';
        $slide->link = $this->view->url(array('service' => 'desarrollo-web'), 'services');
        $slides[] = $slide;
        $slide = new stdClass();
        $slide->order = 2;
        $slide->image = $this->view->baseUrl('/img/slides/diseno-web.jpg');
        $slide->title = 'Second Thumbnail label';
        //$slide->text = 'Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.';
        $slide->link = $this->view->url(array('service' => 'diseno-web'), 'services');
        $slides[] = $slide;
        $slide = new stdClass();
        $slide->order = 3;
        $slide->image = $this->view->baseUrl('/img/slides/redes-sociales.jpg');
        $slide->title = 'Third Thumbnail label';
        //$slide->text = 'Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.';
        $slide->link = $this->view->url(array('service' => 'redes-sociales'), 'services');
        $slides[] = $slide;
        $slide = new stdClass();
        $slide->order = 4;
        $slide->image = $this->view->baseUrl('/img/slides/web-marketing.jpg');
        $slide->title = 'Fourth Thumbnail label';
        //$slide->text = 'Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.';
        $slide->link = $this->view->url(array('service' => 'email-marketing'), 'services');
        $slides[] = $slide;
        $this->view->assign(array(
        	'slides' => $slides,
        ));
    }

    public function sitemapAction() {
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