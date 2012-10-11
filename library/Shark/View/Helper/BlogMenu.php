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
 * @subpackage View.Helpers
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
/**
 * Blog menu helper.
 *
 * @category   Library
 * @package    Shark
 * @subpackage View.Helpers
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
class Shark_View_Helper_BlogMenu extends Zend_View_Helper_Abstract
{
    /**
     * @var Zend_Controller_Request_Abstract $request Request object.
     */
    protected $request;

    /**
     * @var Zend_Controller_Router $router Router object.
     */
    protected $router;

    /**
     * Builds a menu for blog categories.
     *
     * @param array $items Categories.
     *
     * @return string Blog menu.
     */
    public function blogMenu($items)
    {
        $container = new Zend_Navigation();
        foreach ($items as $item) {
            $page = Zend_Navigation_Page::factory(
                array(
                    'label' => $item->title,
                    'route' => 'blog-category',
                    'params' => array(
                        'category_alias' => $item->alias,
                    ),
                )
            );
            $container->addPage($page);
        }
        $frontController = Zend_Controller_Front::getInstance();
        $this->request = $frontController->getRequest();
        $this->router = $frontController->getRouter();
        return $this->_buildList($container);
    }

    /**
     * Builds a menu list for the given container.
     *
     * @param Zend_Navigation_Container $container Navigation container.
     *
     * @return string
     */
    private function _buildList($container)
    {
        $output = '<ul class="nav">';
        foreach ($container as $page) {
            $output .= $this->_buildItem($page);
        }
        $output .= '</ul>';
        return $output;
    }

    /**
     * Builds a menu item for the given page.
     *
     * @param Zend_Navigation_Page $page Navigation page.
     *
     * @return string
     */
    private function _buildItem($page)
    {
        $output = '';
        $itemClasses = array();
        $linkClasses = array();
        $hasSubItems = count($page->pages) > 0;
        $href = $page->href;
        if ($page->isActive()) {
            $itemClasses[] = 'active';
        } else if ($this->request->getParam('category_alias') === $page->getParam('category_alias')) {
            $itemClasses[] = 'active';
        }
        if ($hasSubItems) {
            //$href = '#';
            $itemClasses[] = 'haschild';
            $linkClasses[] = 'haschild';
        }
        $output .= '<li class="' . implode(' ', $itemClasses) . '">';
        $output .= $this->_buildAnchor($href, $page->title, $page->label);
        if ($hasSubItems) {
            $output .= '<ul class="sub-items">';
            foreach ($page->pages as $subPage) {
                $output .= $this->_buildItem($subPage);
            }
            $output .= '</ul>';
        }
        $output .= '</li>';
        return $output;
    }

    /**
     * Builds a menu anchor.
     *
     * @param string $href           Anchor href attribute.
     * @param string $title          Anchor title attribute.
     * @param string $label          Anchor text.
     * @param array  $dataAttributes Anchor data attributes.
     * @param array  $classes        Tag classes.
     *
     * @return string
     */
    private function _buildAnchor($href, $title, $label, $dataAttributes = array(), $classes = array())
    {
        $data = '';
        foreach ($dataAttributes as $key => $value) {
            $data .= 'data-' . $key . '="' . $value . '"';
        }
        $class = 'class="' . implode(' ', $classes) . '"';
        return '<a href="' . $href . '" title="' . $title . '" ' . $data . ' ' . $class . '>' . $label . '</a>';
    }
}