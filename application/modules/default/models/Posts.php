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
 * @category  Application
 * @package   Models
 * @author    Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright 2012 Shark Web Intelligence
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version   ${CURRENT_VERSION}
 * @link      http://www.sharkwebintelligence.com
 */
/**
 * Blog posts model.
 *
 * @category  Application
 * @package   Models
 * @author    Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright 2012 Shark Web Intelligence
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version   ${CURRENT_VERSION}
 * @link      http://www.sharkwebintelligence.com
 */
class Model_Posts
{
    /**
     * @var Table_Posts $table Table posts.
     */
    protected $table;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->table = new Table_Posts();
    }

    /**
     * Get the blog archive by year.
     *
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function getArchive()
    {
        $select = $this->table->getAdapter()->select();
        $result = $select->from(
            array(
                'p' => 'blog_posts',
            ),
            array(
                'date_raw' => 'p.created_at',
                'month' => new Zend_Db_Expr('DATE_FORMAT(p.created_at, "%M")'),
                'year' => new Zend_Db_Expr('DATE_FORMAT(p.created_at, "%Y")'),
                'date' => new Zend_Db_Expr('CONCAT(DATE_FORMAT(p.created_at, "%Y"), " ", DATE_FORMAT(p.created_at, "%M"))'),
                'posts' => new Zend_Db_Expr('COUNT(*)'),
            )
        )
            ->group('date')
            ->order('p.created_at DESC')
            ->query()
            ->fetchAll();
        return $result;
    }

    /**
     * Gets the post by date and (or) title alias.
     *
     * @param int    $year  Starting year.
     * @param int    $month Starting month.
     * @param int    $day   Starting day.
     * @param int    $limit Posts limit.
     * @param int    $start Page start.
     * @param string $alias Post alias.
     * @param array  $order Ordering.
     *
     * @return mixed
     */
    public function getPosts($year = null, $month = null, $day = null, $limit = 10, $start = 0, $alias = null, $order = array())
    {
        $select = $this->table->select();
        if ($alias) {
            $select->where('alias = ?', $alias);
            return $this->table->fetchRow($select);
        }
        $date = new Zend_Date();
        if ($year) {
            $date->set($year, Zend_Date::YEAR);
        }
        if ($month) {
            $date->set($month, Zend_Date::MONTH);
        }
        if ($year) {
            $date->set($day, Zend_Date::DAY);
        }
        $date->set(23, Zend_Date::HOUR);
        $date->set(59, Zend_Date::MINUTE);
        $date->set(59, Zend_Date::SECOND);
        $select->where('created_at <= ?', $date->toString('YYYY-MM-dd H:m:s'));
        $select->order($order);
        $adapter = new Zend_Paginator_Adapter_DbTableSelect($select);
        $count = $this->table->getAdapter()->select();
        $count->reset(Zend_Db_Select::COLUMNS);
        $count->reset(Zend_Db_Select::FROM);
        $count->from(
            'blog_posts',
            new Zend_Db_Expr('COUNT(*) AS `zend_paginator_row_count`')
        );
        $adapter->setRowCount($count);
        $paginator = new Zend_Paginator($adapter);
        $paginator->setItemCountPerPage($limit)
            ->setCurrentPageNumber($start);
        return $paginator;
    }
}
