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
 * @package    Blog
 * @subpackage Controllers
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
/**
 * RSS controller.
 *
 * @category   Site
 * @package    Blog
 * @subpackage Controllers
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
class Blog_RssController extends Shark_Controller_Action
{

    protected $limit = 10;

    /**
     * Disable view and layout.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout(true);
    }

    /**
     * RSS blog category action.
     *
     * @return void
     */
    public function blogAction()
    {
        $request = $this->getRequest();
        $category = $request->getParam('category_alias');
        $posts = array();
        $model = new Model_Categories();
        $this->limit = $request->getQuery('limit', $this->limit);
        $title = $this->view->title . ' - Blog';
        $description = $this->view->description;
        $link = $this->view->absoluteUrl($this->view->url());
        if (!empty($category) && ($category = $model->getByAlias($category))) {
            $posts = $category->posts;
            $title .= ' - ' . $category->title;
            $description = $category->description;
        } else {
            $date = Zend_Date::now();
            $year = $date->toString(Zend_Date::YEAR);
            $month = $date->toString(Zend_Date::MONTH);
            $day = $date->toString(Zend_Date::DAY);
            $model = new Model_Posts();
            $posts = $model->getPosts($year, $month, $day, $this->limit);
        }
        return $this->_sendFeed($title, $description, $link, $posts);
    }

    /**
     * RSS blog author action.
     *
     * @return void
     */
    public function authorAction()
    {
        $request = $this->getRequest();
        $author = $request->getParam('username');
        $model = new Model_Users();
        if (!empty($author) && ($author = $model->findByUsername($author))) {
            $posts = $author->posts;
        } else {
            return $this->_forward('blog');
        }
        $this->limit = $request->getQuery('limit', $this->limit);
        $title = $this->view->title . ' - Blog - ' . $author->name;
        $description = $author->bio;
        $posts = $author->posts;
        $link = $this->view->absoluteUrl($this->view->url(array('username' => $author->username), 'blog-author'));
        return $this->_sendFeed($title, $description, $link, $posts);
    }

    /**
     * Sends the feed to the browser.
     *
     * @param string $title       Feed title.
     * @param string $description Feed description.
     * @param string $link        Feed link
     * @param array  $posts       List of posts.
     *
     * @return string
     */
    private function _sendFeed($title, $description, $link, $posts)
    {
        $feedData = array(
            'title' => $title,
            'description' => $description,
            'link' => $link,
            'charset' => 'utf8',
            'entries' => array(),
        );
        foreach ($posts as $post) {
            $date = new Zend_Date($post->created_at, null, 'es_MX');
            $year = $date->get(Zend_Date::YEAR);
            $month = $date->get(Zend_Date::MONTH);
            $day = $date->get(Zend_Date::DAY);
            $link = array(
                'year' => $year,
                'month' => $month,
                'day' => $day,
                'alias' => $post->alias,
            );
            $feedData['entries'][] = array(
                'title' => $post->title,
                'description' => $post->intro,
                'link' => $this->view->absoluteUrl($this->view->url($link, 'blog-post')),
                'guid' => $this->view->absoluteUrl($this->view->url($link, 'blog-post')),
            );
        }
        $feed = Zend_Feed::importArray($feedData, 'rss');
        echo $feed->saveXML();
    }
}