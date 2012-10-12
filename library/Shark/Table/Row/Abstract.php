<?php
/**
 * Abstract.php
 *
 * @category   Library
 * @package    Shark
 * @subpackage Row
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @link       http://sharkwebintelligence.com
 */
/**
 * Custom table adapter.
 *
 * @category   Library
 * @package    Shark
 * @subpackage Row
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @link       http://sharkwebintelligence.com
 */
abstract class Shark_Table_Row_Abstract extends Zend_Db_Table_Row_Abstract
{

    /**
     * Pre-insert logic.
     *
     * @return void
     */
    // @codingStandardsIgnoreStart
    protected function _insert()
    {
        $this->_updateTimestamp('created_at');
        $this->_updateAlias();
    }
    // @codingStandardsIgnoreEnd

    /**
     * Pre-update logic.
     *
     * @return void
     */
    // @codingStandardsIgnoreStart
    protected function _update()
    {
        $this->_updateTimestamp('modified_at');
        $this->_updateAlias();
    }
    // @codingStandardsIgnoreEnd

    /**
     * Update row date column.
     *
     * @param string $column Column name (created_at or modified_at).
     *
     * @return void
     */
    private function _updateTimestamp($column)
    {
        $info = $this->_table->info();
        if (array_search($column, $info['cols'])) {
            $this->$column = $this->_getDate();
        }
    }

    /**
     * Normalize alias.
     *
     * @return void
     */
    private function _updateAlias()
    {
        $info = $this->_table->info();
        if (array_search('alias', $info['cols']) && empty($this->_data['alias']) && in_array('title', $info['cols'])) {
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
            if (empty($this->alias)) {
                $value = $data->title;
            } else {
                $value = $data->alias;
            }
            $this->alias = strtolower($filters->filter($value));
        }
    }

    /**
     * Gets the current date with the specified format.
     *
     * @param string $format Date format. Defaults to YYYY-MM-dd HH:mm:ss.
     *
     * @return string Current date.
     */
    private function _getDate($format = Shark_Table_Abstract::DATE_FORMAT)
    {
        return Zend_Date::now()->toString($format);
    }
}