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
 * @package    Core
 * @subpackage Models
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
/**
 * Users entity.
 *
 * @category   Application
 * @package    Core
 * @subpackage Models
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
class Entity_User extends Zend_Db_Table_Row_Abstract
{
    /**
     * Gets the category posts.
     *
     * @return Zend_Db_Table_Rowset
     */
    public function getPosts()
    {
        /*
        $table = $this->getTable()->getAdapter();
        $select = $table->select();
        $select->from(array('p' => 'blog_posts'), array('p.*', 'zend_paginator_row_count' => new Zend_Db_Expr('COUNT(p.*)')))
            ->joinInner(array('u' => 'core_users'), 'u.*');
        $select->where('p.user_id = ?', $this->id);
        $adapter = new Zend_Paginator_Adapter_DbTableSelect($select);
        $count = clone $select;
        $count->reset(Zend_Db_Select::COLUMNS);
        $count->reset(Zend_Db_Select::FROM);
        $count->from(
            array('p' => 'blog_posts'), array('zend_paginator_row_count' => new Zend_Db_Expr('COUNT(*)'))
        )->joinInner(array('u' => 'core_users'), 'u.id = p.user_id');
        $adapter->setRowCount($count);
        $paginator = new Zend_Paginator($adapter);
        var_dump($paginator);
        exit;
        foreach ($paginator as $item) {
            var_dump($item);
        }
        exit;
        return $paginator;
        var_dump($paginator);
        exit;
        */
        $posts = $this->findDependentRowset('Table_Posts', null, $this->getTable()->select()->order('created_at DESC'));
        return $posts;
    }

    /**
     * Proxies method to the dependent tables.
     *
     * @param string $name Property name.
     *
     * @return mixed
     */
    public function __get($name)
    {
        $method = 'get' . ucfirst($name);
        if (in_array($method, get_class_methods($this))) {
            return $this->$method();
        } else {
            return parent::__get($name);
        }
    }
}