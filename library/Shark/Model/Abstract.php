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
 * @subpackage Models
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
/**
 * Model class.
 *
 * @category   Library
 * @package    Shark
 * @subpackage Models
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
abstract class Shark_Model_Abstract
{

    /**
     * @var Shark_Table_Abstract $_table Model table.
     */
    private $_table;

    /**
     * Find one row.
     *
     * @param int $id Row id.
     *
     * @return null|Shark_Row_Abstract
     */
    public function findOne($id)
    {
        $result = $this->find($id);
        if ($result->count()) {
            return $result->current();
        }
    }

    /**
     * Gets rows from the database that match the given filters.
     *
     * @param array $filters Filters.
     *
     * @return mixed|Zend_Db_Table_Rowset_Abstract Results.
     */
    public function fetchFiltered($filters)
    {
        $select = $this->select();
        foreach ($filters as $filter) {
            $condition = $filter[0];
            $value = $filter[1];
            $select->where($condition, $value);
        }

        return $this->fetchAll($select);
    }

    /**
     * Finds an item by its alias.
     *
     * @param string $alias Item alias.
     *
     * @return Shark_Entity_Abstract Item found.
     * @throws Shark_Model_Exception If no item found.
     * @throws Shark_Exception If alias column is not in the row.
     */
    public function findByAlias($alias)
    {
        $info = $this->info();
        if (in_array('alias', $info['cols'])) {
            $result = $this->fetchFiltered(
                array(
                    array('alias = ?', $alias),
                )
            );
            if ($result->count()) {
                return $result->current();
            }
            throw new Shark_Model_Exception("Item with alias $alias not found.");
        } else {
            throw new Shark_Exception("Column alias not found in the row.");
        }
    }

    /**
     * Gets a paginator object with the given filters and ordering.
     *
     * @param int   $limit    Limit rows.
     * @param int   $start    Starting page.
     * @param array $filters  Where filters.
     * @param array $ordering Ordering columns
     *
     * @return Zend_Paginator Paginator with results.
     */
    public function fetchPaged($limit = 10, $start = 0, $filters = array(), $ordering = array())
    {
        $select = $this->select();
        $args = func_get_args();
        if (null !== $filters && is_array($filters)) {
            foreach ($filters as $filter) {
                $keys = array_keys($filter);
                $condition = $keys[0];
                $value = $filter[$condition];
                $select->where($condition, $value);
            }
        }
        $select->order($ordering);
        $adapter = new Zend_Paginator_Adapter_DbTableSelect($select);
        $count = $this->getAdapter()->select();
        $count->reset(Zend_Db_Select::COLUMNS);
        $count->reset(Zend_Db_Select::FROM);
        $count->from(
            $this->getTable()->getTableName(),
            new Zend_Db_Expr('COUNT(*) AS `zend_paginator_row_count`')
        );
        $adapter->setRowCount($count);
        $paginator = new Zend_Paginator($adapter);
        $paginator->setItemCountPerPage($limit)
            ->setCurrentPageNumber($start);

        return $paginator;
    }

    /**
     * Sets the table adapter to use.
     *
     * @param Shark_Table_Abstract $table Table adapter.
     *
     * @return Shark_Model_Abstract Provides a fluent interface.
     */
    public function setTable(Shark_Table_Abstract $table)
    {
        if (is_string($table)) {
            $table = new $table();
        }
        if ($table instanceof Zend_Db_Table_Abstract) {
            $this->_table = $table;
        }

        return $this;
    }

    /**
     * Gets the current used table adapter.
     *
     * @return Shark_Table_Abstract The table adapter this model is using.
     * @throws Shark_Model_Exception If table is not found.
     */
    public function getTable()
    {
        if (null === $this->_table) {
            $parts = explode('_', get_class($this));
            $name = $parts[count($parts) - 1];
            $class = join(
                '_',
                array(
                    $this->_getNamespace(),
                    'Table',
                    $this->_getInflected($name),
                )
            );
            if (class_exists($class)) {
                $this->_table = new $class();
            } else {
                throw new Shark_Model_Exception("Table $class not found.");
            }
        }

        return $this->_table;
    }

    /**
     * Gets the underlying row class name.
     *
     * @return string Entity class name.
     */
    public function getEntityName()
    {
        $rowClass = $this->getRowClass();
        $parts = explode('_', $rowClass);
        $entityName = $parts[count($parts) - 1];
        return $entityName;
    }

    /**
     * Returns an array with key/value pairs.
     *
     * @return array Key/value paris.
     */
    public function getSelectOptions()
    {
        return $this->getAdapter()->fetchPairs($this->select(func_get_args()));
    }

    /**
     * Gets the model namespace.
     *
     * @return string.
     */
    private function _getNamespace()
    {
        $ns = explode('_', get_class($this));

        return $ns[0];
    }

    /**
     * Gets the given name converted from CamelCase to underscore.
     *
     * @param string $name Name to inflect.
     *
     * @return string Filtered class name.
     */
    private function _getInflected($name)
    {
        $inflector = new Zend_Filter_Inflector(':class');
        $inflector->setRules(
            array(
                ':class' => array('Word_CamelCaseToUnderscore'),
            )
        );

        return ucfirst($inflector->filter(array('class' => $name)));
    }

    /**
     * Proxies the method to the underlying table.
     *
     * @param string $name Method name.
     * @param array  $args Method arguments.
     *
     * @return mixed Method return value.
     */
    public function __call($name, $args)
    {
        return call_user_func_array(array($this->getTable(), $name), $args);
    }
}