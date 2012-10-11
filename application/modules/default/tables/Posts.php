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
 * @package   Tables
 * @author    Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright 2012 Shark Web Intelligence
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version   ${CURRENT_VERSION}
 * @link      http://www.sharkwebintelligence.com
 */
/**
 * Posts table.
 *
 * @category  Application
 * @package   Tables
 * @author    Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright 2012 Shark Web Intelligence
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version   ${CURRENT_VERSION}
 * @link      http://www.sharkwebintelligence.com
 */
class Table_Posts extends Zend_Db_Table_Abstract
{
    // @codingStandardsIgnoreStart
    /**
     * @var string $_name Posts table.
     */
    protected $_name = 'blog_posts';

    /**
     * @var string $_rowClass Row class.
     */
    protected $_rowClass = 'Entity_Post';

    /**
     * @var array $_referenceMap Reference map.
     */
    protected $_referenceMap = array(
        'author' => array(
            'columns' => 'user_id',
            'refTableClass' => 'Table_Users',
            'refTableColumns' => 'id',
        ),
        'category' => array(
            'columns' => 'category_id',
            'refTableClass' => 'Table_Categories',
            'refTableColumns' => 'id',
        ),
    );
    // @codingStandardsIgnoreEnd
}