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
 * @category   Application
 * @package    Blog
 * @subpackage Controllers
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
/**
 * Posts controller.
 *
 * @category   Application
 * @package    Blog
 * @subpackage Controllers
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
class Blog_PostsController extends Shark_Controller_Action
{
    /**
     * @var int $limit List limit.
     */
    protected $limit = 20;

    /**
     * @var int $page Starting page.
     */
    protected $page = 0;

    /**
     * Initialize variables.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $request = $this->getRequest();
        $this->limit = (int)Shark_Config::getConfig()->site->blog->limit;
        $this->page = $request->getQuery('page', 0);
        $categoriesModel = new Model_Categories();
        $this->view->assign(
            array(
                'categories' => $categoriesModel->findAll(),
            )
        );
    }

    /**
     * List posts.
     *
     * @return void
     */
    public function listAction()
    {
        $request = $this->getRequest();
        $now = Zend_Date::now();
        $year = $request->getParam('year', $now->toString('YYYY'));
        $month = $request->getParam('month', $now->toString('MM'));
        $day = $request->getParam('day', $now->toString('dd'));
        $model = new Model_Posts();
        $posts = $model->getPosts($year, $month, $day, $this->limit, $this->page, null, array('created_at DESC'));
        $usersModel = new Model_Users();
        $this->view->assign(
            array(
                'posts' => $posts,
                'now' => $now,
                'authors' => $usersModel->findAll(),
            )
        );
    }

    /**
     * View post.
     *
     * @return void
     */
    public function viewAction()
    {
        $request = $this->getRequest();
        $year = $request->getParam('year');
        $month = $request->getParam('month');
        $day = $request->getParam('day');
        $alias = $request->getParam('alias');
        $model = new Model_Posts();
        $post = $model->getPosts($year, $month, $day, $this->limit, $this->page, $alias, array('created_at DESC'));
        $this->view->assign(
            array(
                'post' => $post,
            )
        );
    }

    /**
     * View posts in category.
     *
     * @return void
     */
    public function categoryAction()
    {
        $request = $this->getRequest();
        $model = new Model_Categories();
        $category = $model->getByAlias($request->getParam('category_alias'));
        if (!$category) {
        }
        $this->view->assign(
            array(
                'category' => $category,
            )
        );
    }

    /**
     * View posts form author.
     *
     * @return void
     */
    public function authorAction()
    {
        $request = $this->getRequest();
        $model = new Model_Posts();
        $usersModel = new Model_Users();
        $user = $usersModel->findByUsername($request->getParam('username'));
        if ($user) {
            $this->view->author = $user;
        }
        $users = $usersModel->findAll();
        $this->view->authors = $users;
    }
}