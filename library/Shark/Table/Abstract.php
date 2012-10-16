<?php
/**
 * Abstract.php
 *
 * @category   Library
 * @package    Shark
 * @subpackage Table
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @link       http://sharkwebintelligence.com
 */
/**
 * Custom table adapter.
 *
 * @category   Library
 * @package    Shark
 * @subpackage Config
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @link       http://sharkwebintelligence.com
 */
abstract class Shark_Table_Abstract extends Zend_Db_Table_Abstract
{
    const DATE_FORMAT = 'YYYY-MM-dd HH:mm:ss';

    const DATE_NULL = '0000-00-00 00:00:00';

    /**
     * Initialize object
     *
     * Called from {@link __construct()} as final step of object instantiation.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->_rowClass = $this->getRowClass();
    }

    /**
     * Set up table name including prefix.
     *
     * @return void
     */
    // @codingStandardsIgnoreStart
    protected function _setupTableName()
    {
        $config = $this->_db->getConfig();
        if (isset($config['prefix']) && !empty($config['prefix'])) {
            $this->_name = $config['prefix'] . $this->_name;
        }
        parent::_setupTableName();
    }
    // @codingStandardsIgnoreEnd

    /**
     * Inserts a new row.
     *
     * @param array $data Column-value pairs.
     *
     * @return mixed The primary key of the row inserted.
     */
    public function insert(array $data)
    {
        $this->_updateTimestamp('created_at', $data);
        $this->_updateAlias($data);
        $this->_updateUserId($data);
        return parent::insert($data);
    }

    /**
     * Updates existing rows.
     *
     * @param array        $data  Column-value pairs.
     * @param array|string $where An SQL WHERE clause, or an array of SQL WHERE clauses.
     *
     * @return int The number of rows updated.
     */
    public function update(array $data, $where)
    {
        $this->_updateTimestamp('modified_at', $data);
        $this->_updateAlias($data);
        return parent::update($data, $where);
    }

    /**
     * Get the null date format.
     *
     * @return string Null date format
     */
    public function getNullDate()
    {
        return self::DATE_NULL;
    }

    /**
     * Get the row class.
     *
     * @return string
     */
    public function getRowClass()
    {
        $parts = explode('_', get_class($this));
        $name = $parts[count($parts) - 1];
        $class = join(
            '_',
            array(
                $this->_getNamespace(),
                'Entity',
                $this->_getInflected($name),
            )
        );
        if (class_exists($class)) {
            $this->_rowClass = $class;
        }
        return parent::getRowClass();
    }

    /**
     * Get the table name.
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->_name;
    }

    /**
     * Get the table namespace.
     *
     * @return string
     */
    private function _getNamespace()
    {
        $ns = explode('_', get_class($this));
        return $ns[0];
    }

    /**
     * Get the table name inflected.
     *
     * @param string $name Table name.
     *
     * @return string
     */
    private function _getInflected($name)
    {
        $singularize = new Shark_Filter_Singularize();
        $name = $singularize->filter($name);
        $inflector = new Zend_Filter_Inflector(':class');
        $inflector->setRules(
            array(
                ':class' => array('Word_CamelCaseToUnderscore'),
            )
        );
        return $inflector->filter(array('class' => $name));
    }

    /**
     * Update row date column.
     *
     * @param string $column Column name (created_at or modified_at).
     * @param array  &$data  Column-value pairs.
     *
     * @return void
     */
    private function _updateTimestamp($column, &$data)
    {
        $info = $this->info();
        if (array_search($column, $info['cols'])) {
            $data[$column] = $this->_getDate();
        }
    }

    /**
     * Normalize alias.
     *
     * @param array &$data Column-value pairs.
     *
     * @return void
     */
    private function _updateAlias(&$data)
    {
        $info = $this->info();
        if (array_search('alias', $info['cols']) && empty($data['alias']) && in_array('title', $info)) {
            $filters = new Zend_Filter();
            $filters->addFilter(new Zend_Filter_Alnum(true))
                ->addFilter(new Shark_Filter_Word_Utf8ToAscii())
                ->addFilter(
                    new Zend_Filter_PregReplace(
                        array(
                            'match' => '/\s/',
                            'replace' => '-',
                        )
                    )
                );
            if (empty($data['alias'])) {
                $value = $data['title'];
            } else {
                $value = $data['alias'];
            }
            $data['alias'] = strtolower($filters->filter($value));
        }
    }

    /**
     * Updates the user_id column.
     *
     * @param array &$data Row data.
     *
     * @return void
     */
    private function _updateUserId(&$data)
    {
        $info = $this->info('cols');
        if (in_array('user_id', $info)) {
            $auth = Zend_Auth::getInstance();
            if (!($user = unserialize($auth->getIdentity()))) {
                throw new Exception("This row can not be saved if there's no logged in user");
            }
            $data['user_id'] = $user->id;
        }
    }

    /**
     * Gets the current date with the specified format.
     *
     * @param string $format Date format. Defaults to YYYY-MM-dd HH:mm:ss.
     *
     * @return string Current date.
     */
    private function _getDate($format = self::DATE_FORMAT)
    {
        return Zend_Date::now()->toString($format);
    }
}