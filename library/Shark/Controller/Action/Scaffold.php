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
 * @subpackage Actions
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
/**
 * Scaffolding controller.
 *
 * @category   Library
 * @package    Shark
 * @subpackage Actions
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
abstract class Shark_Controller_Action_Scaffold extends Shark_Controller_Action
{

    /**
     * Controller actions used as CRUD operations.
     */
    const ACTION_INDEX  = 'index';
    const ACTION_LIST   = 'list';
    const ACTION_CREATE = 'create';
    const ACTION_UPDATE = 'update';
    const ACTION_DELETE = 'delete';

    /**
     * Create form button definitions.
     */
    const BUTTON_SAVE       = 'SHARK_BUTTON_SAVE';
    const BUTTON_SAVEEDIT   = 'SHARK_BUTTON_SAVE_AND_EDIT';
    const BUTTON_SAVECREATE = 'SHARK_BUTTON_SAVE_AND_NEW';

    /**
     * Message types.
     */
    const MSG_OK  = 'OK';
    const MSG_ERR = 'ERR';

    /**
     * Identifier used in view generation.
     */
    const CSS_ID  = 'shark';

    /**
     * Default number of items in listing per page.
     */
    const ITEMS_PER_PAGE = 10;

    /**
     * Create form button default labels.
     */
    protected $buttonLabels    = array(
        self::BUTTON_SAVE       => 'SHARK_BUTTON_SAVE',
        self::BUTTON_SAVEEDIT   => 'SHARK_BUTTON_SAVE_AND_EDIT',
        self::BUTTON_SAVECREATE => 'SHARK_BUTTON_SAVE_AND_NEW'
    );

    /**
     * Messages displayed upon record creation, update or deletion.
     */
    protected $messages = array(
        self::ACTION_CREATE => array(
            self::MSG_OK => 'SHARK_SCAFFOLD_ENTITY_CREATED',
            self::MSG_ERR => 'SHARK_SCAFFOLD_ENTITY_CREATE_FAILED'
        ),
        self::ACTION_UPDATE => array(
            self::MSG_OK  => 'SHARK_SCAFFOLD_ENTITY_EDITED',
            self::MSG_ERR => 'SHARK_SCAFFOLD_ENTITY_EDIT_FAILED'
        ),
        self::ACTION_DELETE => array(
            self::MSG_OK  => 'SHARK_SCAFFOLD_ENTITY_DELETED',
            self::MSG_ERR => 'SHARK_SCAFFOLD_ENTITY_DELETE_FAILED'
        ),
    );

    /**
     * Last error message.
     * @var String
     */
    protected $lastError;

    /**
     * Default scaffolding options.
     * @var Array
     */
    private $_options = array(
        'pkEditable'        => false,
        'indexAction'       => false,
        'viewFolder'        => 'scaffold',
        'entityTitle'       => 'entity',
        'createEntityText'  => null,
        'updateEntityText'  => null,
        'deleteEntityText'  => null,
        'readonly'          => false,
        'disabledActions'   => array(),
        'editFormButtons'     => array(
            self::BUTTON_SAVE,
            self::BUTTON_SAVEEDIT,
            self::BUTTON_SAVECREATE
        ),
        'csrfProtected'     => true,
        'customMessenger'   => false,
        'translator'        => null,
        'actionParams'      => null,
        'editLayout'        => null,
        'pagination'        => true,
    );

    /**
     * Different data type definitions and their mapping to
     * a more generic internal data type.
     * @var Array
     */
    private $_dataTypes = array(
        'numeric' => array(
            'tinyint', 'bool', 'smallint', 'int',
            'numeric', 'int4', 'integer', 'mediumint', 'bigint',
            'decimal', 'float', 'double'
        ),
        'text' => array(
            'char', 'bpchar', 'varchar',
            'smalltext', 'text', 'mediumtext', 'longtext'
        ),
        'time' => array('time', 'date', 'datetime', 'timestamp'),
    );
    /**
     * Scaffolding field definitions.
     * @var Array
     */
    private $_fields;

    /**
     * Data providing class.
     * @var Zend_Db_Table_Abstract|Zend_Db_Table_Select|Zend_Db_Select
     */
    private $_dbSource;

    /**
     * Cached table metadata.
     * @var Array
     */
    private $_metaData;

    /**
     * Initializes scaffolding.
     *
     * @param Zend_Db_Table_Abstract|Zend_Db_Select $dbSource Respective model instance
     * @param array                                 $fields   Field definitions
     * @param Zend_Config|array                     $options  Scaffolding options.
     *
     * @return void
     */
    protected function scaffold($dbSource, $fields = array(), $options = null)
    {
        if ($dbSource instanceof Shark_Model_Abstract) {
            $dbSource = $dbSource->getTable();
        }
        // Check arguments.
        if (!($dbSource instanceof Zend_Db_Table_Abstract
            || $dbSource instanceof Zend_Db_Table_Select)
        ) {
            throw new Zend_Controller_Exception(
                'Scaffolding initialization requires an instance of '
                . 'Zend_Db_Table_Abstract or Zend_Db_Table_Select.'
            );
        }

        $this->_dbSource = $dbSource;
        $this->fields = $fields;
        if (is_array($options)) {
            $this->_options = array_merge($this->_options, $options);
        }

        if (isset($this->_options['translator'])
            && !($this->_options['translator'] instanceof Zend_Translate)
        ) {
            throw new Zend_Controller_Exception("'translator' option must be instance of Zend_Translate.");
        } else if (Zend_Registry::isRegistered('Zend_Translate')) {
            $this->_options['translator'] = Zend_Registry::get('Zend_Translate');
        }

        // If readonly restrict all other actions except for index and list
        $modActions = array(self::ACTION_CREATE, self::ACTION_DELETE, self::ACTION_UPDATE);
        if (!empty($options['readonly'])) {
            $this->_options['disabledActions'] = $modActions;
        } else if (count(array_intersect($this->_options['disabledActions'], $modActions)) == 3) {
            // All actions are disabled, apply 'readonly'
            $this->_options['readonly'] = true;
        }
        $this->view->readonly = $this->_options['readonly'];

        $action = $this->getRequest()->getActionName();
        if (in_array($action, $this->_options['disabledActions'])) {
                throw new Zend_Controller_Exception("'$action' action is disabled.");
        }

        // Prepare view variables.
        $this->view->action       = $action;
        $this->view->module       = strtolower($this->getRequest()->getModuleName());
        $this->view->controller   = $this->getRequest()->getControllerName();
        $this->view->actionParams = $this->_options['actionParams'];
        $this->view->currentRoute   = $this->getFrontController()->getRouter()->getCurrentRouteName();
        $this->view->createRoute = sprintf('%s_%s_create', $this->view->module, $this->view->controller);
        $this->view->updateRoute = sprintf('%s_%s_update', $this->view->module, $this->view->controller);
        $this->view->indexRoute = sprintf('%s_%s_list', $this->view->module, $this->view->controller);
        $this->view->deleteRoute = sprintf('%s_%s_delete', $this->view->module, $this->view->controller);

        if (!$this->_options['customMessenger']) {
            $this->view->messages   = $this->_helper->getHelper('FlashMessenger')->getMessages();
        }

        $this->view->entityTitle    = $this->_options['entityTitle'] = $this->_translate($this->_options['entityTitle']);
        $this->view->createEntityText  = $this->_options['createEntityText'];
        $this->view->updateEntityText  = $this->_options['updateEntityText'];
        $this->view->deleteEntityText  = $this->_options['deleteEntityText'];

        // Do not override view script path if the action requested is not
        // one of the standard scaffolding actions
        $scaffActions   = array(self::ACTION_LIST, self::ACTION_INDEX,
                                                    self::ACTION_CREATE, self::ACTION_UPDATE,
                                                    self::ACTION_DELETE);
        $indexAction = false;
        if (!empty($this->_options['indexAction'])) {
            $scaffActions[] = $action;
            $indexAction = true;
        }
        if (in_array($action, $scaffActions)) {
            if ($indexAction) {
                $this->getHelper('ViewRenderer')
                    ->setViewScriptPathSpec(sprintf('%s/index.:suffix', $this->_options['viewFolder']));
                // Call native index action, since it may be overriden in descendant class.
                self::indexAction();
            } else {
                $this->getHelper('ViewRenderer')
                    ->setViewScriptPathSpec(sprintf('%s/:action.:suffix', $this->_options['viewFolder']));
            }
        }
    }

    /**
     * Display the list of entries, as well as optional elements
     * like paginator, search form and sortable headers as specified
     * in field definition.
     *
     * @return void
     */
    public function indexAction()
    {
        $fields = $searchFields = $sortingFields  = array();
        $defSortField   = null;
        $searchForm     = null;
        $searchActive   = false;

        $tableInfo      = $this->_getMetadata();
        $pks            = $tableInfo['primary'];
        $tableRelations = array_keys($tableInfo['referenceMap']);
        $joinOn         = array();

        // Use all fields if no field settings were provided.
        if (!count($this->fields)) {
            $this->fields = array_combine($tableInfo['cols'], array_fill(0, count($tableInfo['cols']), array()));
        } else {
            // Add PK(s) to select query.
            foreach ($pks as $pk) {
                $fields[$tableInfo['name']][] = $pk;
            }
        }

        // Process primary/related table fields.
        $defaultOrder = 1;
        foreach ($this->fields as $columnName => $columnDetails) {
            $tableName      = $tableInfo['name'];
            $defColumnName  = $columnName;
            $this->fields[$columnName]['order'] = $defaultOrder++;

            // Check if the column belongs to a related table.
            $fullColumnName = explode('.', $columnName);
            if (!empty($this->fields[$columnName]['displayField'])) {
                $fullColumnName = explode('.', $this->fields[$columnName]['displayField']);
            }
            if (count($fullColumnName) == 2) {
                $refName = $fullColumnName[0];
                $refDisplayField = $fullColumnName[1];
                // Column is a FK.
                if (in_array($refName, $tableRelations)) {
                    $ruleDetails = $tableInfo['referenceMap'][$refName];
                    // @todo: what if columns are an array?
                    $mainColumn = $ruleDetails['columns'];
                    $refColumn = is_array($ruleDetails['refColumns'])
                        ? array_shift($ruleDetails['refColumns'])
                        : $ruleDetails['refColumns'];
                    $relatedModel         = new $ruleDetails['refTableClass']();
                    $relatedTableMetadata = $relatedModel->info();
                    $relatedTableName     = $relatedTableMetadata['name'];

                    $joinOn[$relatedTableName] = "$tableName.$mainColumn = $relatedTableName.$refColumn";

                    // Change current table and column to be used later.
                    // Aliases are used to evade same column names from joined tables.
                    $tableName  = $relatedTableName;
                    $columnName = array($defColumnName => $refDisplayField);
                } else {
                    $isDependentTableColumn = false;
                    // Check if column is from a dependent table.
                    foreach ($tableInfo['dependentTables'] as $depTableClass) {
                        $dependentTable = new $depTableClass;
                        if (!$dependentTable instanceof Zend_Db_Table_Abstract) {
                            throw new Zend_Controller_Exception('Shark_Controller_Action_Scaffold requires a Zend_Db_Table_Abstract as model providing class.');
                        }

                        $relatedTableMetadata = $dependentTable->info();
                        $references = $relatedTableMetadata['referenceMap'];
                        // Reference with such name may not be defined
                        // or column may not exist (the last means n-n table)
                        if (!isset($references[$refName])
                            || !in_array($refDisplayField, $relatedTableMetadata['cols'])
                        ) {
                            continue;
                        }

                        $ruleDetails = $references[$refName];

                        // @todo: what if columns are an array?
                        $mainColumn = is_array($ruleDetails['refColumns'])
                            ? array_shift($ruleDetails['refColumns'])
                            : $ruleDetails['refColumns'];
                        $refColumn = $ruleDetails['columns'];

                        $relatedTableName = $relatedTableMetadata['name'];
                        $joinOn[$relatedTableName] = "$tableName.$mainColumn = $relatedTableName.$refColumn";

                        // Change current table and column to be used later.
                        // Aliases are used to evade same column names from joined tables.
                        $tableName  = $relatedTableName;
                        $columnName = array($defColumnName => $refDisplayField);

                        $isDependentTableColumn = true;
                        break;
                    }

                    // Column is neither FK nor a dependent table column
                    // so we can't show it, search or sort by it.
                    if (!$isDependentTableColumn) {
                        unset($this->fields[$columnName]);
                        continue;
                    }
                }
            }

            $fields[$tableName][] = $columnName;

            // Prepare search form fields.
            if (!empty($this->fields[$defColumnName]['search'])) {
                $searchFields[$defColumnName] = $columnDetails;
            }

            // Prepare sortable fields.
            if (!empty($this->fields[$defColumnName]['sort'])) {
                $sortingFields[$tableName] = $columnName;
            }

            $this->fields[$defColumnName]['sqlName'] = "$tableName." . (is_array($columnName)
                ? current($columnName)
                : $columnName);

            $defSortField = empty($defSortField)
                ? (empty($this->fields[$defColumnName]['sort']['default'])
                   ? null
                   : $defColumnName)
                : $defSortField;
        }

        if ($this->_dbSource instanceof Zend_Db_Table_Abstract) {
            $select = $this->_dbSource->select();
            $select->from($this->_dbSource, $this->_getFullColumnNames($tableInfo['name'], $fields));
        } else {
            $select = $this->_dbSource;
            $select->from($this->_dbSource->getTable(), $this->_getFullColumnNames($tableInfo['name'], $fields));
        }

        if (count($joinOn)) {
            // Workaround required by Zend_Db_Table_Select.
            $select->setIntegrityCheck(false);
            foreach ($joinOn as $table => $joinCond) {
                $select->joinLeft($table, $joinCond, $this->_getFullColumnNames($table, $fields));
            }
        }

        /**
         * Apply search filter, storing search criteria in session.
         */
        $searchActive = false;
        if (count($searchFields)) {
            // Create unique search session variable.
            // @todo: test if it is unique in ALL cases
            $nsName = $tableInfo['name'] . '_' . join('_', array_keys($searchFields));
            $searchParams   = new Zend_Session_Namespace($nsName);
            $searchForm     = $this->_buildSearchForm($searchFields);

            if ($this->getRequest()->isPost()
                && $searchForm->isValid($this->getRequest()->getPost())
            ) {
                if (isset($_POST['reset'])) {
                    $filterFields = array();
                } else {
                    $filterFields = $searchForm->getValues();
                }
                $searchParams->search   = $filterFields;
            } else {
                $filterFields = isset($searchParams->search) ? $searchParams->search : array();
            }
            $searchForm->populate($filterFields);

            foreach ($filterFields as $field => $value) {
                if ($value || is_numeric($value)) {
                    // Search by date.
                    // Date is a period, need to handle both start and end date.
                    if (strpos($field, self::CSS_ID . '_from')) {
                        $field = str_replace('_' . self::CSS_ID . '_from', '', $field);
                        $select->where("{$tableInfo['name']}.$field >= ?", $value);
                    } elseif (strpos($field, self::CSS_ID . '_to')) {
                        $field = str_replace('_' . self::CSS_ID . '_to', '', $field);
                        $select->where("{$tableInfo['name']}.$field <= ?", $value);
                    } elseif (strpos($field, '_isempty') && $value) {
                        $field = str_replace('_isempty', '', $field);
                        $select->where("{$tableInfo['name']}.$field IS NULL OR {$tableInfo['name']}.$field = ''");
                    } else {
                        // Search all other native fields.
                        if (isset($tableInfo['metadata'][$field])) {
                            $dataType = strtolower($tableInfo['metadata'][$field]['DATA_TYPE']);
                            $fieldType = !empty($this->fields[$field]['fieldType'])
                                ? $this->fields[$field]['fieldType']
                                : '';
                            $tableName = $tableInfo['name'];
                        } else {
                            // Search by related table field.
                            // Column name was normalized, need to find it.
                            $fieldDefs = array_keys($this->fields);
                            $fieldFound = false;
                            foreach ($fieldDefs as $fieldName) {
                                if (strpos($fieldName, '.') !== false && str_replace('.', '', $fieldName) == $field) {
                                    $field = $fieldName;
                                    $fieldFound = true;
                                    break;
                                }
                            }

                            // The submitted form value is not from model, skip it.
                            if (!$fieldFound) {
                                continue;
                            }

                            $dataType = $this->fields[$field]['fieldType'];
                            list($tableName, $field) = explode('.', $this->fields[$field]['sqlName']);
                        }

                        if (in_array($dataType, $this->_dataTypes['text'])
                            || $fieldType == 'text'
                        ) {
                            $select->where("$tableName.$field LIKE ?", '%' . $value . '%');
                        } else {
                            $select->where("$tableName.$field = ?", $value);
                        }
                    }

                    $searchActive = true;
                }
            }
        }

        /**
         * Handle sorting by modifying SQL and building header sorting links.
         */
        $sortField  = $this->_getParam('orderby', 'created_at');
        $sortMode   = $this->_getParam('mode', 'desc') == 'desc' ? 'desc' : 'asc';
        if (!$sortField && $defSortField) {
            $sortField  = $defSortField;
            $sortMode   = $this->fields[$sortField]['sort']['default'] == 'desc' ? 'desc' : 'asc';
        }
        if ($sortField) {
            $select->order("{$this->fields[$sortField]['sqlName']} $sortMode");
        }

        // Sort fields for listing.
        $this->fields = array_filter($this->fields, array($this, '_removeHiddenListItems'));
        uasort($this->fields, array($this, '_sortByListOrder'));

        $this->_prepareHeader($sortField, $sortMode);

        $this->_prepareList($select);

        $this->view->searchActive   = $searchActive;
        $this->view->searchForm     = $searchForm;
        $this->view->primaryKey     = $pks;

        $this->view->canCreate      = !in_array(self::ACTION_CREATE, $this->_options['disabledActions']);
        $this->view->canUpdate      = !in_array(self::ACTION_UPDATE, $this->_options['disabledActions']);
        $this->view->canDelete      = !in_array(self::ACTION_DELETE, $this->_options['disabledActions']);
    }

    /**
     * Alias of index action.
     *
     * @return void
     */
    public function listAction()
    {
        $this->_forward('index');
    }

    /**
     * Handle custom Zend_Db_Select-based queries.
     *
     * @param Zend_Db_Select $select  Select instance.
     * @param array          $fields  Select fields.
     * @param mixed          $options Query options.
     *
     * @return void
     */
    protected function smartQuery($select, $fields = array(), $options = null)
    {
        // Check arguments.
        if (!$select instanceof Zend_Db_Select) {
            throw new Zend_Controller_Exception('Custom select method requires an instance of Zend_Db_Select.');
        }

        $this->fields = $fields;
        if (is_array($options)) {
            $this->_options = array_merge($this->_options, $options);
        }

        if (isset($this->_options['translator'])
            && !($this->_options['translator'] instanceof Zend_Translate)
        ) {
            throw new Zend_Controller_Exception("'translator' option must be instance of Zend_Translate.");
        }

        // Do not override view script path if the action requested is not
        // one of the standard scaffolding actions
        $action = $this->getRequest()->getActionName();
        $scaffActions   = array(
            self::ACTION_LIST,
            self::ACTION_INDEX,
            self::ACTION_CREATE,
            self::ACTION_UPDATE,
            self::ACTION_DELETE,
        );
        $indexAction = false;
        if (!empty($this->_options['indexAction'])) {
                $scaffActions[] = $action;
                $indexAction = true;
        }
        if (in_array($action, $scaffActions)) {
            if ($indexAction) {
                $this->getHelper('ViewRenderer')
                    ->setViewScriptPathSpec(sprintf('%s/index.:suffix', $this->_options['viewFolder']));
            } else {
                $this->getHelper('ViewRenderer')
                    ->setViewScriptPathSpec(sprintf('%s/:action.:suffix', $this->_options['viewFolder']));
            }
        }

        $searchFields = $sortingFields  = array();
        $defSortField   = null;
        $searchForm     = null;
        $searchActive   = false;

        // Process primary/related table fields.
        $defaultOrder = 1;
        foreach ($this->fields as $columnName => $columnDetails) {
            $defColumnName = $columnName;

            if (strpos($columnName, '.')) {
                list($tableName, $columnName) = explode('.', $columnName);
                $this->fields[$defColumnName]['sqlName'] ="$tableName.$columnName";
            } else {
                $this->fields[$defColumnName]['sqlName'] ="$columnName";
            }

            // Prepare search form fields.
            if (!empty($this->fields[$defColumnName]['search'])) {
                $searchFields[$defColumnName] = $columnDetails;
            }

            // Prepare sortable fields.
            if (!empty($this->fields[$defColumnName]['sort'])) {
                $sortingFields[$tableName] = $columnName;
            }

            $this->fields[$defColumnName]['order'] = $defaultOrder++;

            $defSortField = empty($defSortField)
                ? (empty($this->fields[$defColumnName]['sort']['default'])
                   ? null
                   : $defColumnName)
                : $defSortField;
        }

        /**
         * Apply search filter, storing search criteria in session.
         */
        $searchActive = false;
        if (count($searchFields)) {
            // Create unique search session variable.
            // @todo: test if it is unique in ALL cases
            $nsName = join('_', array_keys($searchFields));
            $searchParams   = new Zend_Session_Namespace($nsName);
            $searchForm     = $this->_buildQuerySearchForm($searchFields);

            if ($this->getRequest()->isPost()
                && $searchForm->isValid($this->_getAllParams())
            ) {
                if (isset($_POST['reset'])) {
                    $filterFields = array();
                } else {
                    $filterFields = $searchForm->getValues();
                }
                $searchParams->search   = $filterFields;
            } else {
                $filterFields = isset($searchParams->search) ? $searchParams->search : array();
            }
            $searchForm->populate($filterFields);

            foreach ($filterFields as $field => $value) {
                if ($value || is_numeric($value)) {
                    // Treat date fields specially.
                    $dateFrom = $dateTo = false;
                    $searchEmpty = false;
                    if (strpos($field, self::CSS_ID . '_from')) {
                        $field = str_replace('_' . self::CSS_ID . '_from', '', $field);
                        $dateFrom = true;
                    } elseif (strpos($field, self::CSS_ID . '_to')) {
                        $field = str_replace('_' . self::CSS_ID . '_to', '', $field);
                        $dateTo = true;
                    } elseif (strpos($field, '_isempty') && $value) {
                        $field = str_replace('_isempty', '', $field);
                        $searchEmpty = true;
                    }

                    // Column name was normalized, need to find it.
                    $fieldDefs = array_keys($this->fields);
                    $fieldFound = false;
                    foreach ($fieldDefs as $fieldName) {
                        if ($fieldName == $field
                            || (strpos($fieldName, '.') !== false && str_replace('.', '', $fieldName) == $field)
                        ) {
                            $field = $fieldName;
                            $fieldFound = true;
                            break;
                        }
                    }

                    // The submitted form value is not from field definitions, skip it.
                    if (!$fieldFound) {
                        continue;
                    }

                    // Search by empty field
                    // @todo: handle aggregation - use HAVING instead of WHERE
                    if ($searchEmpty) {
                        if ($this->fields[$field]['aggregate']) {
                            $method = 'having';
                        } else {
                            $method = 'where';
                        }

                        $select->$method("{$this->fields[$field]['sqlName']} IS NULL OR {$this->fields[$field]['sqlName']} = 0");
                    } elseif (in_array($this->fields[$field]['dataType'], $this->_dataTypes['time'])) {
                        // Date is a period, need to handle both start and end date.
                        if (!empty($dateFrom)) {
                            $select->where("{$this->fields[$field]['sqlName']} >= ?", $value);
                        }
                        if (!empty($dateTo)) {
                            $select->where("{$this->fields[$field]['sqlName']} <= ?", $value);
                        }
                    } elseif (in_array($this->fields[$field]['dataType'], $this->_dataTypes['text'])) {
                        $select->where("{$this->fields[$field]['sqlName']} LIKE ?", $value);
                    } else {
                        $select->where("{$this->fields[$field]['sqlName']} = ?", $value);
                    }
                    $searchActive = true;
                }
            }
        }

        /**
         * Handle sorting by modifying SQL and building header sorting links.
         */
        $sortField  = $this->_getParam('orderby');
        $sortMode   = $this->_getParam('mode') == 'desc' ? 'desc' : 'asc';
        if (!$sortField && $defSortField) {
            $sortField  = $defSortField;
            $sortMode   = $this->fields[$sortField]['sort']['default'] == 'desc'
                ? 'desc'
                : 'asc';
        }
        if ($sortField) {
            $select->order("{$this->fields[$sortField]['sqlName']} $sortMode");
        }

        // Sort fields for listing.
        $this->fields = array_filter($this->fields, array($this, '_removeHiddenListItems'));
        uasort($this->fields, array($this, '_sortByListOrder'));

        $this->_prepareHeader($sortField, $sortMode);

        $this->_prepareList($select);

        $this->view->searchActive   = $searchActive;
        $this->view->searchForm     = $searchForm;

        $this->view->canCreate      = !in_array(self::ACTION_CREATE, $this->_options['disabledActions']);
        $this->view->canUpdate      = !in_array(self::ACTION_UPDATE, $this->_options['disabledActions']);
        $this->view->canDelete      = !in_array(self::ACTION_DELETE, $this->_options['disabledActions']);

        // Prepare other view variables.
        $this->view->action       = $this->getRequest()->getActionName();
        $this->view->module       = strtolower($this->getRequest()->getModuleName());
        $this->view->controller   = $this->getRequest()->getControllerName();
        $this->view->actionParams = $this->_options['actionParams'];
    }

    /**
     * Initializes custom select query search form.
     *
     * @param array $fields list of searchable fields.
     *
     * @return Zend_Form instance of form object
     */
    private function _buildQuerySearchForm(array $fields)
    {
        $datePickerFields   = array();
        $form               = array();

        foreach ($fields as $columnName => $columnDetails) {
            if (empty($columnDetails['dataType'])) {
                throw new Zend_Controller_Exception("No type definition provided for '$columnName'.");
            }

            $defColumnName = $columnName;
            // Column name must be normalized
            // (Zend_Form_Element::filterName does it anyway).
            $columnName = str_replace('.', '', $columnName);
            $dataType = $columnDetails['dataType'];
            $fieldType = !empty($columnDetails['fieldType']) ? $columnDetails['fieldType'] : null;

            $matches = array();
            if (isset($columnDetails['search']['options'])
                && is_array($columnDetails['search']['options'])
            ) {
                $options = $columnDetails['search']['options'];
                $options[''] = $this->_translate('SHARK_ALL');
                ksort($options);

                if ($fieldType == 'radio') {
                    $elementType = 'radio';
                } else {
                    $elementType = 'select';
                }

                $form['elements'][$columnName] = array(
                    $elementType,
                    array(
                        'multiOptions' => $options,
                        'label' => $this->_getColumnTitle($defColumnName, empty($columnDetails['translate'])),
                        'class' => self::CSS_ID . '-search-' . $elementType,
                        'value' => '',
                        'disableTranslator' => empty($columnDetails['translate'])
                    ),
                );
            } elseif (in_array($dataType, $this->_dataTypes['time'])) {
                $form['elements'][$columnName . '_' . self::CSS_ID . '_from'] = array(
                    'text', array(
                        'label'         => $this->_getColumnTitle($defColumnName) . ' from',
                        'class'         => self::CSS_ID . '-search-' . $fieldType,
                    ),
                );

                $form['elements'][$columnName . '_' . self::CSS_ID . '_to'] = array(
                    'text', array(
                        'label' => 'to',
                        'class' => self::CSS_ID . '-search-' . $fieldType,
                    ),
                );

                if ($fieldType == 'jsPicker') {
                    $datePickerFields[] = $columnName . '_' . self::CSS_ID . '_from';
                    $datePickerFields[] = $columnName . '_' . self::CSS_ID . '_to';
                }
            } elseif (in_array($dataType, $this->_dataTypes['text'])) {
                $length     = isset($columnDetails['size']) ? $columnDetails['size'] : '';
                $maxlength  = isset($columnDetails['maxlength']) ? $columnDetails['maxlength'] : '';

                $form['elements'][$columnName] = array(
                    'text',
                    array(
                        'class'     => self::CSS_ID . '-search-text',
                        'label'     => $this->_getColumnTitle($defColumnName),
                        'size'      => $length,
                        'maxlength' => $maxlength,
                    ),
                );
            } elseif (in_array($dataType, $this->_dataTypes['numeric'])) {
                if ($fieldType == 'checkbox') {
                    // By default integer values are displayed as text fields
                    $form['elements'][$columnName] = array(
                        'checkbox',
                        array(
                            'class' => self::CSS_ID . '-search-radio',
                            'label' => $this->_getColumnTitle($defColumnName),
                        ),
                    );
                } else {
                    $form['elements'][$columnName] = array(
                        'text',
                        array(
                            'class' => self::CSS_ID . '-search-text',
                            'label' => $this->_getColumnTitle($defColumnName),
                        ),
                    );
                }
            } else {
                throw new Zend_Controller_Exception("Fields of type '$dataType' are not searchable.");
            }

            // Allow to search empty records
            if (isset($columnDetails['search']['empty'])) {
                $elementName = "{$columnName}_isempty";
                $form['elements'][$elementName] = array(
                    'checkbox',
                    array(
                        'class' => self::CSS_ID . '-search-radio',
                        'label' => (empty($columnDetails['search']['emptyLabel'])
                                    ? $this->_getColumnTitle($defColumnName) . ' ' . _('is empty')
                                    : $columnDetails['search']['emptyLabel']),
                        ),
                    );
                // Save custom attributes
                if (isset($columnDetails['attribs'])
                    && is_array($columnDetails['attribs'])
                ) {
                    $form['elements'][$elementName][1] = array_merge($form['elements'][$elementName][1], $columnDetails['attribs']);
                }
            }

            // Do not search by non-empty field value
            if (isset($columnDetails['search']['emptyOnly'])) {
                unset($form['elements'][$columnName]);
            }

            // Save custom attributes
            if (isset($columnDetails['attribs'])
                && is_array($columnDetails['attribs'])
            ) {
                $form['elements'][$columnName][1] = array_merge($form['elements'][$columnName][1], $columnDetails['attribs']);
            }
        }

        $form['elements']['submit'] = array(
            'submit',
            array(
                'ignore'   => true,
                'class' => 'btn btn-large btn-primary',
                'label' => 'Search',
            ),
        );

        $form['elements']['reset'] = array(
            'submit',
            array(
                'ignore'   => true,
                'class' => 'btn btn-large',
                'label' => 'Reset',
                'onclick' => 'Shark.resetForm(this.form);'
            ),
        );

        // Load JS files
        if (count($datePickerFields)) {
            $this->loadDatePicker($datePickerFields);
        }

        $form['action'] = $this->view->url();

        return $this->prepareSearchForm($form);
    }

    /**
     * Create entity handler.
     *
     * @return void
     */
    public function createAction()
    {
        $info = $this->_getMetadata();

        if (count($info['primary']) == 0) {
            throw new Zend_Controller_Exception('The model you provided does not have a primary key.');
        }

        $form = $this->_buildEditForm();

        if ($this->getRequest()->isPost()
            && $form->isValid($this->_getAllParams())
        ) {
            list($values, $relData) = $this->_getDbValuesInsert($form->getValues());
            if ($this->beforeCreate($form, $values)) {
                try {
                    Zend_Db_Table::getDefaultAdapter()->beginTransaction();
                    $insertId = $this->_dbSource->insert($values);
                    // Save many-to-many field to the corresponding table
                    if (count($relData)) {
                        foreach ($relData as $m2mData) {
                            $m2mTable   = $m2mData[0];
                            $m2mValues  = $m2mData[1];

                            if (count($m2mValues)) {
                                $m2mInfo    = $m2mTable->info();
                                $tableClass = get_class($this->_dbSource);
                                foreach ($m2mInfo['referenceMap'] as $rule => $ruleDetails) {
                                    if ($ruleDetails['refTableClass'] == $tableClass) {
                                        $selfRef = $ruleDetails['columns'];
                                    } else {
                                        $relatedRef = $ruleDetails['columns'];
                                    }
                                }

                                foreach ($m2mValues as $v) {
                                    $m2mTable->insert(array($selfRef => $insertId, $relatedRef => $v));
                                }
                            }
                        }
                    }

                    Zend_Db_Table::getDefaultAdapter()->commit();

                    $this->_helper->FlashMessenger($this->_getActionMessage(self::ACTION_CREATE, self::MSG_OK));

                    if ($this->afterCreate($form, $insertId)) {
                        if (isset($_POST[self::BUTTON_SAVE])) {
                            //$redirect = $this->view->url(array(), $this->view->indexRoute);
                            //$redirect = "{$this->view->module}/{$this->view->controller}/index";
                            $this->gotoRoute($this->view->indexRoute);
                        } elseif (isset($_POST[self::BUTTON_SAVEEDIT])) {
                            //$redirect = $this->view->url(array('id' => $insertId), $this->view->updateRoute);
                            //$redirect = "{$this->view->module}/{$this->view->controller}/update/id/$insertId";
                            $this->gotoRoute($this->view->updateRoute, array('id' => $insertId));
                        } elseif (isset($_POST[self::BUTTON_SAVECREATE])) {
                            //$redirect = $this->view->url(array(), $this->view->createRoute);
                            //$redirect = "{$this->view->module}/{$this->view->controller}/create";
                            $this->gotoRoute($this->view->createRoute);
                        }

                        //$this->_redirect($redirect);
                    }
                }
                catch (Zend_Db_Exception $e) {
                    $this->lastError = $e->getMessage();
                    Zend_Db_Table::getDefaultAdapter()->rollBack();
                    $this->_helper->FlashMessenger($this->_getActionMessage(self::ACTION_CREATE, self::MSG_ERR));
                }
            }
        }

        $this->view->form           = $form;
        if (isset($this->_options['editLayout'])) {
            $this->_helper->layout->setLayout($this->_options['editLayout']);
        }
    }

    /**
     * Entity deletion handler.
     *
     * @return void
     */
    public function deleteAction()
    {

        $params = $this->_getAllParams();
        $info = $this->_getMetadata();

        if (count($info['primary']) == 0) {
            throw new Zend_Controller_Exception('The model you provided does not have a primary key, scaffolding is impossible!');
        }
        // Compound key support
        $primaryKey = array();
        foreach ($params AS $k => $v) {
            if (in_array($k, $info['primary'])) {
                $primaryKey["$k = ?"] = $v;
            }
        }

        try {
            $row = $this->_dbSource->fetchAll($primaryKey);
            if ($row->count()) {
                $row = $row->current();
            } else {
                throw new Zend_Controller_Exception('Invalid request.');
            }

            $originalRow = clone $row;

            if ($this->beforeDelete($originalRow)) {
                $row->delete();
                $this->_helper->FlashMessenger($this->_getActionMessage(self::ACTION_DELETE, self::MSG_OK));
                if ($this->afterDelete($originalRow)) {
                    //$this->_redirect($this->view->url(array(), $this->view->indexRoute));
                    //$this->_redirect("{$this->view->module}/{$this->view->controller}/index");
                    $this->gotoRoute($this->view->indexRoute);
                }
            } else {
                $this->_helper->FlashMessenger($this->_getActionMessage(self::ACTION_DELETE, self::MSG_ERR));
                //$this->_redirect($this->view->url(array(), $this->view->indexRoute));
                //$this->_redirect("{$this->view->module}/{$this->view->controller}/index");
                $this->gotoRoute($this->view->indexRoute);
            }
        } catch (Zend_Db_Exception $e) {
            $this->lastError = $e->getMessage();
            $this->_helper->FlashMessenger($this->_getActionMessage(self::ACTION_DELETE, self::MSG_OK));
            //$this->_redirect($this->view->url(array(), $this->view->indexRoute));
            //$this->_redirect("{$this->view->module}/{$this->view->controller}/index");
            $this->gotoRoute($this->view->indexRoute);
        }
    }

    /**
     * Entity update handler.
     *
     * @return void
     */
    public function updateAction()
    {
        $info = $this->_getMetadata();

        if (count($info['primary']) == 0) {
            throw new Zend_Controller_Exception('The model you provided does not have a primary key.');
        }

        // Support compound keys
        $primaryKey = array();
        $params = $this->_getAllParams();
        foreach ($params AS $k => $v) {
            if (in_array($k, $info['primary'])) {
                $primaryKey["$k = ?"] = $v;
            }
        }

        $entity = $this->_dbSource->fetchAll($primaryKey);
        if ($entity->count() == 1) {
            $entity = $entity->current()->toArray();
        } else {
            throw new Zend_Controller_Exception('Invalid primary key specified.');
        }

        $form = $this->_buildEditForm($entity);
        $populate = true;

        if ($this->getRequest()->isPost() && $form->isValid($params)) {
            $populate = false;
            $formValues = $form->getValues();
            $pkValue = $formValues[array_shift($info['primary'])];

            list($values, $where, $relData) = $this->_getDbValuesUpdate($entity, $formValues);

            // Save common submitted fields
            if (!is_null($values) && !is_null($where)) {
                if ($this->beforeUpdate($form, $values)) {

                    try {
                        Zend_Db_Table::getDefaultAdapter()->beginTransaction();
                        $this->_dbSource->update($values, $where);
                        // Save many-to-many field to the corresponding table
                        if (count($relData)) {
                            foreach ($relData as $m2mData) {
                                $m2mTable   = $m2mData[0];
                                $m2mValues  = is_array($m2mData[1]) ? $m2mData[1] : array();

                                $m2mInfo    = $m2mTable->info();
                                $tableClass = get_class($this->_dbSource);
                                foreach ($m2mInfo['referenceMap'] as $rule => $ruleDetails) {
                                    if ($ruleDetails['refTableClass'] == $tableClass) {
                                        $selfRef = $ruleDetails['columns'];
                                    } else {
                                        $relatedRef = $ruleDetails['columns'];
                                    }
                                }

                                $m2mTable->delete("$selfRef = $pkValue");
                                foreach ($m2mValues as $v) {
                                    $m2mTable->insert(array($selfRef => $pkValue, $relatedRef => $v));
                                }
                            }
                        }

                        Zend_Db_Table::getDefaultAdapter()->commit();
                        $this->_helper->FlashMessenger($this->_getActionMessage(self::ACTION_UPDATE, self::MSG_OK));

                        if ($this->afterUpdate($form, $pkValue)) {
                            //var_dump($this->view->url(array(), $this->view->indexRoute));exit;
                            //$this->_redirect($this->view->url(array(), $this->view->indexRoute));
                            $this->gotoRoute($this->view->indexRoute);
                            //$this->_redirect("{$this->view->module}/{$this->view->controller}/index");
                        }
                    } catch (Zend_Db_Exception $e) {
                        $this->lastError = $e->getMessage();
                        Zend_Db_Table::getDefaultAdapter()->rollBack();
                        $this->_helper->FlashMessenger($this->_getActionMessage(self::ACTION_UPDATE, self::MSG_ERR));
                    }
                }
            }
        }

        if ($populate === true) {
            // Load common field values
            foreach ($entity as $field => $value) {
                // Apply field modifier if any
                if (isset($this->fields[$field]['loadModifier'])) {
                    if (function_exists($this->fields[$field]['loadModifier'])) {
                        $entity[$field] = call_user_func($this->fields[$field]['loadModifier'], $value);
                    } else {
                        $entity[$field] = $this->fields[$field]['loadModifier'];
                    }
                }
            }

            // Load many-to-many field values
            foreach ($this->fields as $field => $fieldDetails) {
                if (isset($fieldDetails['manyToManyTable'])) {
                    $m2mTable = $fieldDetails['manyToManyTable'];
                    $m2mInfo = $m2mTable->info();

                    $tableClass = get_class($this->_dbSource);
                    foreach ($m2mInfo['referenceMap'] as $rule => $ruleDetails) {
                        if ($ruleDetails['refTableClass'] == $tableClass) {
                            $selfRef = $ruleDetails['columns'];
                        } else {
                            $relatedRef = $ruleDetails['columns'];
                        }
                    }

                    $m2mValues = $m2mTable->select()
                        ->from($m2mTable, $relatedRef)
                        ->where("$selfRef = ?", $primaryKey)
                        ->query(Zend_Db::FETCH_ASSOC)->fetchAll();

                    $multiOptions = array();
                    foreach ($m2mValues as $_value) {
                        $multiOptions[] = $_value[$relatedRef];
                    }

                    // Column name must be normalized
                    // (Zend_Form_Element::filterName does it anyway).
                    $field = str_replace('.', '', $field);
                    $entity[$field] = $multiOptions;
                }
            }

            $form->setDefaults($entity);
        }

        $this->view->form = $form;
        if (isset($this->_options['editLayout'])) {
            $this->_helper->layout->setLayout($this->_options['editLayout']);
        }
    }

    /**
     * Formats the post-action message according to the template.
     *
     * @param string $action  Action name
     * @param string $msgType Message type
     *
     * @return string
     */
    private function _getActionMessage($action, $msgType)
    {
        return sprintf($this->_translate($this->messages[$action][$msgType]), $this->_options['entityTitle']);
    }

    /**
     * Generates the create/update form based on table metadata
     * and field definitions provided at initialization.
     *
     * @param array $entityData Currently editable entity data.
     *
     * @return Zend_Form
     */
    private function _buildEditForm(array $entityData = array())
    {
        $info       = $this->_getMetadata();
        $metadata   = $info['metadata'];
        $tableClass = get_class($this->_dbSource);
        $action     = $this->getRequest()->getActionName();
        $form       = array();
        $rteFields  = $datePickerFields = array();
        $handledRefs  = array();

            // Look through native table columns.
        foreach ($metadata as $columnName => $columnDetails) {

            // Primary key is hidden by default.
            if (in_array($columnName, $info['primary']) && $this->_options['pkEditable'] == false) {
                $form['elements'][$columnName] = array(
                    'hidden', array(
                        'value' => 0,
                    )
                );
                continue;
            }

            // Skip the field?
            if (!empty($this->fields[$columnName]['hide'])) {
                if ($this->fields[$columnName]['hide'] === true) {
                    continue;
                }
                if (is_string($this->fields[$columnName]['hide'])) {
                    $this->fields[$columnName]['hide'] = explode(',', $this->fields[$columnName]['hide']);
                }
                if (is_array($this->fields[$columnName]['hide'])
                    && in_array('edit', $this->fields[$columnName]['hide'])
                ) {
                    continue;
                }
            }

            // Is the field mandatory?
            if (isset($this->fields[$columnName]['required'])) {
                if (is_string($this->fields[$columnName]['required'])) {
                    if ($this->fields[$columnName]['required'] == self::ACTION_CREATE
                        && $action != self::ACTION_CREATE
                    ) {
                        $required = false;
                    }
                } else {
                    $required = $this->fields[$columnName]['required'];
                }
            } else {
                $required = $columnDetails['NULLABLE'] == 1 ? false : true;
            }

            // Does it have a default value?
            if (!is_null($columnDetails['DEFAULT'])) {
                $defaultValue = $columnDetails['DEFAULT'];
            } else {
                $defaultValue = '';
            }

            // Specially handle the column if it is a foreign key
            // and build necessary select/multicheckbox field.
            if (!empty($this->fields[$columnName]['displayField'])) {
                list($refName, $displayField) = explode('.', $this->fields[$columnName]['displayField']);
                if (!empty($info['referenceMap'][$refName])) {
                    $ruleDetails = $info['referenceMap'][$refName];
                    $refColumn = is_array($ruleDetails['refColumns'])
                        ? array_shift($ruleDetails['refColumns'])
                        : $ruleDetails['refColumns'];

                    $options = array();
                    // Is value required?
                    if (!$required) {
                        $options[''] = '';
                    }

                    $relatedModel = new $ruleDetails['refTableClass']();
                    foreach ($relatedModel->fetchAll()->toArray() as $k => $v) {
                        $key = $v[$refColumn]; // obtain value of partner column
                        if (!isset($options[$key])) {
                            $options[$key] = $v[$displayField];
                        }
                    }

                    $form['elements'][$columnName] = array(
                        'select', array(
                            'multiOptions'  => $options,
                            'label'         => $this->_getColumnTitle($columnName, empty($this->fields[$columnName]['translate'])),
                            'description'   => $this->_getColumnDescription($columnName, empty($this->fields[$columnName]['translate'])),
                            'required'      => $required,
                            'value'         => $defaultValue,
                            'disableTranslator' => empty($this->fields[$columnName]['translate'])
                        )
                    );
                } else {
                    throw new Zend_Controller_Exception("No references are defined for '$displayField'.");
                }

                $handledRefs[] = $this->fields[$columnName]['displayField'];
                continue;
            }

            $elementOptions = array(
                'label'         => $this->_getColumnTitle($columnName, empty($this->fields[$columnName]['translate'])),
                'description'   => $this->_getColumnDescription($columnName, empty($this->fields[$columnName]['translate'])),
                'required'      => $required,
                'value'         => $defaultValue,
                'validators'    => isset($this->fields[$columnName]['validators'])
                    ? $this->_prepareValidators($columnName, $this->fields[$columnName]['validators'], $entityData)
                    : array(),
                'filters'       => isset($this->fields[$columnName]['filters'])
                    ? $this->fields[$columnName]['filters']
                    : array(),
            );

            // Build enum column as select or multicheckbox.
            $enumDefinition = null;
            if (isset($this->fields[$columnName]['options'])) {
                // Pseudo data type
                $dataType = 'options';
            } elseif (preg_match('/^enum/i', $columnDetails['DATA_TYPE'])) {
                $enumDefinition = $columnDetails['DATA_TYPE'];
                $dataType       = 'enum';
            } else {
                $dataType = strtolower($columnDetails['DATA_TYPE']);
            }

            $textFieldOptions   = array();
            $textFieldType      = null;

            if (!empty($this->fields[$columnName]['fieldType'])) {
                switch ($this->fields[$columnName]['fieldType']) {
                case 'textarea':
                    $textFieldType  = 'textarea';
                    break;
                case 'richtextarea':
                    $textFieldType  = 'textarea';
                    $rteFields[]    = $columnName;
                    break;
                case 'text':
                    $textFieldType = 'text';
                    break;
                case 'password':
                    $textFieldType = 'password';
                    break;
                case 'ckeditor':
                    $textFieldType = 'ckeditor';
                    break;
                case 'jsPicker':
                    $datePickerFields[] = $columnName;
                    break;
                }

                if (in_array($textFieldType, array('text', 'password'))) {
                    if (isset($this->fields[$columnName]['size'])) {
                        $textFieldOptions['size'] = $this->fields[$columnName]['size'];
                    }
                    if (isset($this->fields[$columnName]['maxlength'])) {
                        $textFieldOptions['maxlength'] = $this->fields[$columnName]['maxlength'];
                    } elseif (isset($metadata[$columnName]['LENGTH'])) {
                        $textFieldOptions['maxlength'] = $metadata[$columnName]['LENGTH'];
                    }
                } elseif ($textFieldType == 'textarea') {
                    if (isset($this->fields[$columnName]['cols'])) {
                        $textFieldOptions['cols'] = $this->fields[$columnName]['cols'];
                    }
                    if (isset($this->fields[$columnName]['rows'])) {
                        $textFieldOptions['rows'] = $this->fields[$columnName]['rows'];
                    }
                }
            }

            if (in_array($dataType, array('enum', 'options'))) {
                // Build radio/select element from enum/options
                // Try to parse enum definition
                if (isset($enumDefinition)) {
                    preg_match_all('/\'(.*?)\'/', $enumDefinition, $matches);

                    $options = array();
                    foreach ($matches[1] as $match) {
                        $options[$match] = ucfirst($match);
                    }
                } else {
                    // Not enum - use options provided
                    $options = $this->fields[$columnName]['options'];
                }

                if (!empty($this->fields[$columnName]['fieldType'])
                    && $this->fields[$columnName]['fieldType'] == 'radio'
                ) {
                    $elementType = 'radio';
                } else {
                    $elementType = 'select';
                }
                foreach ($options as $value => $label) {
                    $options[$value] = $this->_translate($label);
                }

                $form['elements'][$columnName] = array(
                    $elementType,
                    array_merge(
                        array(
                            'multiOptions'  => $options,
                            'disableTranslator' => empty($this->fields[$columnName]['translate'])
                        ),
                        $elementOptions
                    )
                );
            } else if (in_array($dataType, $this->_dataTypes['numeric'])) {
                // Generate fields for numerics.
                if (!empty($this->fields[$columnName]['fieldType'])
                    && $this->fields[$columnName]['fieldType'] == 'checkbox'
                ) {
                    $form['elements'][$columnName] = array(
                        'checkbox',
                        $elementOptions
                    );
                } else {
                    $form['elements'][$columnName] = array(
                            'text',
                            array_merge(array('size' => 10), $elementOptions)
                    );
                }
            } else if (in_array($dataType, $this->_dataTypes['text'])) {
                // Generate single-line input or multiline input for string fields.
                if (in_array($dataType, array('char', 'bpchar', 'varchar', 'smalltext'))) {
                    $form['elements'][$columnName] = array(
                        $textFieldType ? $textFieldType : 'text',
                        array_merge($elementOptions, $textFieldOptions)
                    );
                } else {
                    $form['elements'][$columnName] = array(
                        $textFieldType ? $textFieldType : 'textarea',
                        array_merge($elementOptions, $textFieldOptions)
                    );
                }
            } else if (in_array($dataType, $this->_dataTypes['time'])) {
                $form['elements'][$columnName] = array(
                    'text',
                    $elementOptions
                );
            } else {
                throw new Zend_Controller_Exception("Unsupported data type '$dataType' encountered, scaffolding is not possible.");
            }

            // Save custom attributes
            if (isset($this->fields[$columnName]['attribs'])
                && is_array($this->fields[$columnName]['attribs'])
            ) {
                $form['elements'][$columnName][1] = array_merge($form['elements'][$columnName][1], $this->fields[$columnName]['attribs']);
            }
        }

        /**
         * Look for additional field definitions (not from current model).
         */
        foreach ($this->fields as $columnName => $columnDetails) {

            if (in_array($columnName, $handledRefs)) {
                continue;
            }

            $fullColumnName = explode('.', $columnName);
            if (count($fullColumnName) == 2) {
                $refName = $fullColumnName[0];
                $refDisplayField = $fullColumnName[1];
                foreach ($info['dependentTables'] as $depTableClass) {
                    $dependentTable = new $depTableClass;
                    if (!$dependentTable instanceof Zend_Db_Table_Abstract) {
                        throw new Zend_Controller_Exception('Shark_Controller_Action_Scaffold requires a Zend_Db_Table_Abstract as model providing class.');
                    }

                    $references = $dependentTable->info(Zend_Db_Table::REFERENCE_MAP);
                    // Reference with such name may not be defined...
                    if (!isset($references[$refName])) {
                        continue;
                    } elseif ($references[$refName]['refTableClass'] == $tableClass) {
                        // For now, skip back-references (reference to the current entity table).
                        // @todo: would it be nice to update dependent table columns?
                        continue;
                    } else {
                        // All is fine, this is a true n-n table.
                        $reference = $references[$refName];
                    }

                    $optionsTable = new $reference['refTableClass'];
                    // Auto-detect PK based on metadata
                    if (!isset($reference['refColumns'])) {
                        $optionsTableInfo = $optionsTable->info();
                        $reference['refColumns'] = array_shift($optionsTableInfo['primary']);
                    }

                    // Value required?
                    $required = isset($columnDetails['required']) && $columnDetails['required'] ? true : false;

                    $options = array();
                    foreach ($optionsTable->fetchAll()->toArray() AS $k => $v) {
                        $key = $v[$reference['refColumns']];
                        if (!isset($options[$key])) {
                            $options[$key] = $v[$refDisplayField];
                        }
                    }

                    if (!empty($columnDetails['fieldType']) && $columnDetails['fieldType'] == 'multicheckbox') {
                        $elementType = 'MultiCheckbox';
                    } else {
                        $elementType = 'Multiselect';
                    }

                    // Column name must be normalized
                    // (Zend_Form_Element::filterName does it anyway).
                    $formColumnName = str_replace('.', '', $columnName);
                    $form['elements'][$formColumnName] = array(
                        $elementType, array(
                            'multiOptions' => $options,
                            'label' => $this->_getColumnTitle($columnName, empty($columnDetails['translate'])),
                            'description'   => $this->_getColumnDescription($columnName, empty($columnDetails['translate'])),
                            'required'  => $required,
                            'validators'    => isset($this->fields[$columnName]['validators'])
                                ? $this->_prepareValidators($columnName, $this->fields[$columnName]['validators'], $entityData)
                                : array(),
                            'disableTranslator' => empty($columnDetails['translate'])
                        )
                    );

                    // Save manyToMany table information.
                    $this->fields[$columnName]['manyToManyTable'] = $dependentTable;
                    break;
                }
            }

            // Save custom attributes
            if (isset($this->fields[$columnName]['attribs'])
                && is_array($this->fields[$columnName]['attribs'])
            ) {
                $form['elements'][$columnName][1] = array_merge($form['elements'][$columnName][1], $this->fields[$columnName]['attribs']);
            }
        }

        // Cross Site Request Forgery protection
        if ($this->_options['csrfProtected']) {
            $form['elements']['csrf_hash'] = array('hash', array('salt' => 'sea_salt_helps'));
        }

        // Generate create form buttons
        if ($action == self::ACTION_CREATE) {
            foreach ($this->_options['editFormButtons'] as $btnId) {
                $buttonClasses = array('btn', 'btn-large', self::CSS_ID . '-' . $btnId);
                switch ($btnId) {
                case self::BUTTON_SAVE:
                    $buttonClasses[] = 'btn-primary';
                    break;
                case self::BUTTON_SAVECREATE:
                    $buttonClasses[] = 'btn-success';
                    break;
                case self::BUTTON_SAVEEDIT:
                    break;
                }
                $form['elements'][$btnId] = array(
                    'submit',
                    array(
                        'label' => $this->buttonLabels[$btnId],
                        'class' => implode(' ', $buttonClasses),
                    ),
                );
            }
        } else {
            $form['elements'][self::BUTTON_SAVE] = array(
                'submit',
                array(
                    'label' => $this->buttonLabels[ self::BUTTON_SAVE],
                    'class' => 'btn btn-large btn-primary',
                ),
            );
        }

        $form['action'] = $this->view->url();

        // Enable rich text editor for necessary fields
        if (count($rteFields)) {
            $this->loadRichTextEditor($rteFields);
        }

        // Enable date picker
        if (count($datePickerFields)) {
            $this->loadDatePicker($datePickerFields);
        }

        // Additionally process form
        return $this->prepareEditForm($form);
    }

    /**
     * Initializes entity search form.
     *
     * @param array $fields list of searchable fields.
     *
     * @return Zend_Form instance of form object
     */
    private function _buildSearchForm(array $fields)
    {
        $info               = $this->_getMetadata();
        $metadata           = $info['metadata'];
        $tableRelations     = array_keys($info['referenceMap']);

        $datePickerFields   = array();
        $form               = array();

        foreach ($fields as $columnName => $columnDetails) {
            $defColumnName = $columnName;
            if (isset($metadata[$columnName])) {
                $dataType = strtolower($metadata[$columnName]['DATA_TYPE']);
                $fieldType = !empty($columnDetails['fieldType']) ? $columnDetails['fieldType'] : '';
            } else {
                /**
                 * Check if the column belongs to a related table.
                 * @todo: support for n-n relations.
                 */
                $fieldType = '';
                $fullColumnName = explode('.', $columnName);
                if (count($fullColumnName) == 2) {
                    // Column is a FK.
                    if (in_array($fullColumnName[0], $tableRelations)) {
                        $ruleDetails = $info['referenceMap'][$fullColumnName[0]];
                        // @todo: what if columns are an array?
                        $refColumn = is_array($ruleDetails['refColumns'])
                            ? array_shift($ruleDetails['refColumns'])
                            : $ruleDetails['refColumns'];

                        $relatedModel         = new $ruleDetails['refTableClass'];
                        $relatedTableInfo = $relatedModel->info();
                        $relatedTableMetadata = $relatedTableInfo['metadata'];

                        $dataType = strtolower($relatedTableMetadata[$fullColumnName[1]]['DATA_TYPE']);
                        $fieldType = !empty($columnDetails['fieldType'])
                            ? $columnDetails['fieldType']
                            : '';

                        // Save data type for further usage.
                        $this->fields[$columnName]['fieldType'] = $dataType;
                    }

                    // Column name must be normalized
                    // (Zend_Form_Element::filterName does it anyway).
                    $columnName = str_replace('.', '', $columnName);
                }
            }

            $matches = array();
            $set = false;
            if (isset($metadata[$columnName])
                && preg_match('/^enum/i', $metadata[$columnName]['DATA_TYPE'])
                || (isset($columnDetails['search']['options'])
                && is_array($columnDetails['search']['options'])
                && $set = true)
            ) {
                $options = array();
                // Try to use the specified options
                if ($set) {
                    $options = $columnDetails['search']['options'];
                } elseif (preg_match_all('/\'(.*?)\'/', $metadata[$columnName]['DATA_TYPE'], $matches)) {
                    // or extract options from enum
                    foreach ($matches[1] as $match) {
                        $options[$match] = $match;
                    }
                }
                $options[''] = $this->_translate('SHARK_ALL');
                ksort($options);

                if (!empty($columnDetails['fieldType'])
                    && $columnDetails['fieldType'] == 'radio'
                ) {
                    $elementType = 'radio';
                } else {
                    $elementType = 'select';
                }

                $form['elements'][$columnName] = array(
                    $elementType,
                    array(
                        'multiOptions' => $options,
                        'label' => $this->_getColumnTitle($defColumnName, empty($columnDetails['translate'])),
                        'class' => self::CSS_ID . '-search-' . $elementType,
                        'value' => '',
                        'disableTranslator' => empty($columnDetails['translate'])
                    )
                );
            } elseif (in_array($dataType, $this->_dataTypes['time'])) {
                $form['elements'][$columnName . '_' . self::CSS_ID . '_from'] = array(
                    'text', array(
                        'label' => $this->_getColumnTitle($defColumnName) . ' from',
                        'class' => self::CSS_ID . '-search-' . $dataType . '-' . $fieldType,
                    )
                );

                $form['elements'][$columnName . '_' . self::CSS_ID . '_to'] = array(
                    'text', array(
                        'label' => 'to',
                        'class' => self::CSS_ID . '-search-' . $dataType . '-' . $fieldType,
                    )
                );

                if ($fieldType == 'jsPicker') {
                        $datePickerFields[] = $columnName . '_' . self::CSS_ID . '_from';
                        $datePickerFields[] = $columnName . '_' . self::CSS_ID . '_to';
                }
            } elseif (in_array($dataType, $this->_dataTypes['text'])) {
                $length     = isset($columnDetails['size']) ? $columnDetails['size'] : '';
                $maxlength  = isset($columnDetails['maxlength'])
                    ? $columnDetails['maxlength']
                    : isset($metadata[$columnName]['LENGTH'])
                        ? $metadata[$columnName]['LENGTH']
                        : '';

                $form['elements'][$columnName] = array(
                    'text',
                    array(
                        'class'     => 'search-text',
                        'label'     => $this->_getColumnTitle($defColumnName),
                        'size'      => $length,
                        'maxlength' => $maxlength,
                    )
                );
            } elseif (in_array($dataType, $this->_dataTypes['numeric'])) {
                // Specially handle the column if it is a foreign key
                // and build necessary select/multicheckbox field.
                if (!empty($this->fields[$columnName]['displayField'])) {
                    list($refName, $displayField) = explode('.', $this->fields[$columnName]['displayField']);
                    if (!empty($info['referenceMap'][$refName])) {
                        $ruleDetails = $info['referenceMap'][$refName];
                        $refColumn = is_array($ruleDetails['refColumns'])
                            ? array_shift($ruleDetails['refColumns'])
                            : $ruleDetails['refColumns'];

                        $options = array();
                        $options[''] = $this->_translate('SHARK_ALL');

                        $relatedModel = new $ruleDetails['refTableClass']();
                        foreach ($relatedModel->fetchAll()->toArray() as $k => $v) {
                            $key = $v[$refColumn]; // obtain value of partner column
                            if (!isset($options[$key])) {
                                    $options[$key] = $v[$displayField];
                            }
                        }
                        ksort($options);

                        if (!empty($columnDetails['fieldType']) && $columnDetails['fieldType'] == 'radio') {
                            $elementType = 'radio';
                        } else {
                            $elementType = 'select';
                        }

                        $form['elements'][$columnName] = array(
                            $elementType, array(
                                'multiOptions'  => $options,
                                'label'         => $this->_getColumnTitle($columnName, empty($columnDetails['translate'])),
                                'class'         => 'search-select',
                                'disableTranslator' => empty($columnDetails['translate'])
                            )
                        );
                    } else {
                        throw new Zend_Controller_Exception("No references are defined for '$displayField'.");
                    }
                } else {
                    $form['elements'][$columnName] = array(
                        !empty($columnDetails['fieldType']) && $columnDetails['fieldType'] == 'checkbox'
                            ? 'checkbox'
                            : 'text',
                        array(
                            'class' => 'search-radio',
                            'label' => $this->_getColumnTitle($columnName),
                        )
                    );
                }
            } else {
                throw new Zend_Controller_Exception("Fields of type $dataType are not searchable.");
            }

            // Allow to search empty records
            if (isset($this->fields[$defColumnName]['search']['empty'])) {
                $elementName = "{$columnName}_isempty";
                $form['elements'][$elementName] = array(
                    'checkbox',
                     array(
                        'class' => self::CSS_ID . '-search-radio',
                        'label' => (empty($this->fields[$defColumnName]['search']['emptyLabel'])
                            ? $this->_getColumnTitle($defColumnName) . ' ' . _('is empty')
                            : $this->fields[$defColumnName]['search']['emptyLabel']),
                     )
                );
                // Save custom attributes
                if (isset($this->fields[$defColumnName]['attribs'])
                    && is_array($this->fields[$defColumnName]['attribs'])
                ) {
                    $form['elements'][$elementName][1] = array_merge($form['elements'][$elementName][1], $this->fields[$defColumnName]['attribs']);
                }
            }

            // Do not search by non-empty field value
            if (isset($this->fields[$defColumnName]['search']['emptyOnly'])) {
                unset($form['elements'][$columnName]);
            }

            // Save custom attributes
            if (isset($this->fields[$defColumnName]['attribs'])
                && is_array($this->fields[$defColumnName]['attribs'])
            ) {
                $form['elements'][$columnName][1] = array_merge($form['elements'][$columnName][1], $this->fields[$defColumnName]['attribs']);
            }
        }

        $form['elements']['submit'] = array(
            'submit',
            array(
                'ignore'   => true,
                'class' => 'btn btn-large btn-primary',
                'label' => 'SHARK_BUTTON_SEARCH',
            )
        );

        $form['elements']['reset'] = array(
            'submit',
            array(
                'ignore'   => true,
                'class' => 'btn btn-large',
                'label' => 'SHARK_BUTTON_RESET',
                'onclick' => 'Shark.resetForm(this.form);'
            ),
        );

        // Load JS files
        if (count($datePickerFields)) {
            $this->loadDatePicker($datePickerFields);
        }

        $form['action'] = $this->view->url();

        return $this->prepareSearchForm($form);
    }

    /**
     * Filters form values making them ready to be used by Zend_Db_Table_Abstract.
     *
     * @param array $values Form values
     *
     * @return array $values Filtered values
     */
    private function _getDbValues(array $values)
    {
        if (count($values) > 0) {
            if (isset($values['csrf_hash'])) {
                unset($values['csrf_hash']);
            }
            unset($values['submit']);
        }

        return $values;
    }

    /**
     * Prepare form values for insertion. Applies field save modifiers
     * and handles many-to-many synthetic fields.
     *
     * @param array $values Initial values
     *
     * @return array $values Modified values
     */
    private function _getDbValuesInsert(array $values)
    {
        $values = $this->_getDbValues($values);
        $relData= array();

        if (count($values) > 0) {
            $info = $this->_getMetadata();
            if (!$this->_options['pkEditable']) {
                foreach ($info['primary'] AS $primaryKey) {
                    unset($values[$primaryKey]);
                }
            }
        }

        foreach ($values AS $field => $value) {
            $originalField = $field;
            // Many-to-many field has to be saved into another table
            // Column name was normalized, need to find it.
            $fields = array_keys($this->fields);
            foreach ($fields as $fieldName) {
                if (strpos($fieldName, '.') !== false
                    && str_replace('.', '', $fieldName) == $field
                ) {
                    $field = $fieldName;
                    break;
                }
            }

            if (isset($this->fields[$field]['manyToManyTable'])) {
                // Many-to-many field has to be saved into another table.
                $relData[] = array($this->fields[$field]['manyToManyTable'], $value);
                unset($values[$originalField]);
            } else {
                // Apply field modifier if any
                if (isset($this->fields[$field]['saveModifier'])) {
                        $values[$field] = call_user_func($this->fields[$field]['saveModifier'], $value);
                }
            }
        }

        return array($values, $relData);
    }

    /**
     * Prepare form values for update. Applies field save modifiers
     * and handles many-to-many synthetic fields.
     *
     * @param array $entity Original values (before update).
     * @param array $values Mew values.
     *
     * @return array modified values in form array($values => array, $where => string).
     */
    private function _getDbValuesUpdate(array $entity, array $values)
    {
        $values = $this->_getDbValues($values);
        $info   = $this->_getMetadata();
        $where  = array();
        $update = array();
        $relData= array();

        foreach ($values AS $field => $value) {
            // PK must be used in where clause.
            if (in_array($field, $info['primary'])) {
                $where[] = $this->_dbSource->getAdapter()->quoteInto("$field = ?", $entity[$field]);
            }

            // Original table column.
            if (in_array($field, $info['cols'])) {
                // Normal table field has to be directly saved
                if (!(isset($this->fields[$field]['required']) && $this->fields[$field]['required'] == 'onCreate' && empty($value))) {
                    // Apply field modifier if any
                    if (isset($this->fields[$field]['saveModifier'])) {
                        $update[$field] = call_user_func($this->fields[$field]['saveModifier'], $value);
                    } else {
                        $update[$field] = $value;
                    }
                }
            } else {
                // Column name was normalized, need to find it.
                $fields = array_keys($this->fields);
                foreach ($fields as $fieldName) {
                    if (strpos($fieldName, '.') !== false && str_replace('.', '', $fieldName) == $field) {
                        $field = $fieldName;
                        break;
                    }
                }
                if (isset($this->fields[$field]['manyToManyTable'])) {
                    // Many-to-many field has to be saved into another table.
                    $relData[] = array($this->fields[$field]['manyToManyTable'], $value);
                }
            }
        }

        if (count($where) > 0) {
            $where = implode(" AND ", $where);
            return array($update, $where, $relData);
        } else {
            return array(null, null, null);
        }
    }

    /**
     * Prepare header.
     *
     * @param string $sortField Field to sort.
     * @param string $sortMode  Sort direction.
     *
     * @return void
     */
    private function _prepareHeader($sortField, $sortMode)
    {
        $header = array();
        foreach ($this->fields as $columnName => $columnDetails) {
            if (!empty($columnDetails['hide']) && ($columnDetails['hide'] === true
                || in_array('list', explode(',', $columnDetails['hide'])))
            ) {
                continue;
            }

            $name = $this->_translate($this->_getColumnTitle($columnName));
            // Generate sorting link
            if (!empty($this->fields[$columnName]['sort'])) {

                $currentMode = ($sortField == $columnName ? $sortMode : '');

                if ($currentMode == 'asc') {
                    $counterOrder   = 'desc';
                    $class          = self::CSS_ID . '-sort-desc';
                } elseif ($currentMode == 'desc') {
                    $counterOrder   = 'asc';
                    $class          = self::CSS_ID . '-sort-asc';
                } else {
                    $counterOrder   = 'asc';
                    $class          = '';
                }

                $sortParams = array(
                    'orderby'   => $columnName,
                    'mode'      => $counterOrder
                );

                $href = $this->view->url() . '?' . http_build_query($sortParams);
                $header[$columnName] = "<a class=\"" . self::CSS_ID . "-sort-link $class\" href=\"$href\">$name</a>";
            } else {
                $header[$columnName] = $name;
            }
        }

        $this->view->headers = $header;
        return $header;
    }

    /**
     * Prepares the list of records. Optionally applies field listing modifiers.
     *
     * @param array $select Entries to be displayed.
     *
     * @return array $list Resulting list of entries.
     */
    private function _prepareList($select)
    {
        // Enable paginator if needed
        if (!empty($this->_options['pagination'])) {
            $pageNumber = $this->_getParam('page');
            $paginator = Zend_Paginator::factory($select);

            $paginator->setCurrentPageNumber($pageNumber);
            $itemPerPage = isset($this->_options['pagination']['itemsPerPage'])
                ? $this->_options['pagination']['itemsPerPage']
                : self::ITEMS_PER_PAGE;
            $paginator->setItemCountPerPage($itemPerPage);

            $items = $paginator->getItemsByPage($pageNumber);

            if ($items instanceof Zend_Db_Table_Rowset) {
                $items = $items->toArray();
            } elseif ($items instanceof ArrayIterator) {
                $items = $items->getArrayCopy();
            }

            $this->view->paginator = $paginator;
            $this->view->pageNumber = $pageNumber;
        } else {
            $items = $select->query()->fetchAll();
        }

        $info = $this->_getMetadata();
        $itemList = $origItemList = array();

        foreach ($items as $item) {
            // Convert to array if object.
            if (is_object($item)) {
                $item = (array)$item;
            }

            if (isset($item['is_active'])) {
                $item['is_active'] = $this->_translate((boolean)$item['is_active'] ? 'SHARK_YES' : 'SHARK_NO');
            }

            $origRow = array();

            foreach ($this->fields as $columnName => $columnDetails) {
                // Table fields have fully-qualified SQL name.
                if (strpos($columnDetails['sqlName'], '.')) {
                    // If alias exist let's try alias.
                    // @todo: or column not found by its SQL primary name,
                    if (!empty($item[$columnName])) {
                        $column = $columnName;
                    } else {
                        list($table, $column) = explode('.', $columnDetails['sqlName']);
                    }
                } else {
                    // Computed fields have alias only.
                    $column = $columnName;
                }

                // Null values may be returned.
                $value  = !empty($item[$column]) ? $item[$column] : null;

                // Save original value for possbile usage.
                $origValue  = $value;

                // Call list view modifier for specific column if set
                if (isset($columnDetails['listModifier'])) {
                    if (isset($columnDetails['modifierParams'])) {
                        array_unshift($columnDetails['modifierParams'], (object)$item);
                        $value = call_user_func_array($columnDetails['listModifier'], $columnDetails['modifierParams']);
                    } else {
                        $value = call_user_func($columnDetails['listModifier'], (object)$item);
                    }
                }

                // Translate the field if necessary.
                if (!empty($columnDetails['translate'])) {
                    $value = $this->view->translate($value);
                }

                if (isset($columnDetails['strip_tags']) && (boolean)$columnDetails['strip_tags']) {
                    $value = strip_tags($value);
                }

                $row[$columnName] = $value;

                if ($value != $origValue) {
                    $origRow[$columnName] = $origValue;
                }
            }

            // Fetch PK(s).
            if (!is_null($info)) {
                $keys = array();
                foreach ($info['primary'] as $pk) {
                    $keys[$pk] = $item[$pk];
                }
                $row['pkParams'] = $keys;
            }

            $itemList[]     = $row;
            $origItemList[] = $origRow;
        }

        $this->view->items      = $itemList;
        $this->view->origItems  = $origItemList;
        return $itemList;
    }

    /**
     * Retrieve model table metadata.
     *
     * @return array
     */
    private function _getMetadata()
    {
        if (is_null($this->_metaData)) {
            if ($this->_dbSource instanceof Zend_Db_Table_Abstract) {
                $this->_metaData = $this->_dbSource->info();
            } elseif ($this->_dbSource instanceof Zend_Db_Table_Select) {
                $this->_metaData = $this->_dbSource->getTable()->info();
            }
        }

        return $this->_metaData;
    }

    /**
     * Get fully qualified (table.column) colunm names.
     *
     * @param string $table  Table.
     * @param array  $fields Fields
     *
     * @return array
     */
    private function _getFullColumnNames($table, $fields)
    {
        $result = array();
        foreach ($fields[$table] as $field) {
            if (is_array($field)) {
                $fieldName = current($field);
                $alias = array_search($fieldName, $field);
                $field = $fieldName;
                $result[] = "$table.$field AS $alias";
            } else {
                $result[] = "$table.$field";
            }
        }
        return $result;
    }

    /**
     * Looks if there is a custom defined name for the column for displaying
     *
     * @param string  $columnName Column name.
     * @param boolean $translate  Whether to translate or not.
     *
     * @return string $columnLabel
     */
    private function _getColumnTitle($columnName, $translate = true)
    {
        if (isset($this->fields[$columnName]['title'])) {
            $title = $this->fields[$columnName]['title'];
        } else {
            $title = ucfirst($columnName);
        }

        if ($translate) {
            return $this->_translate($title);
        } else {
            return $title;
        }
    }

    /**
     * Looks if there is a custom defined name for the column for displaying
     *
     * @param string  $columnName Column name.
     * @param boolean $translate  Whether to translate or not.
     *
     * @return String $columnLabel
     */
    private function _getColumnDescription($columnName, $translate = false)
    {
        if (isset($this->fields[$columnName]['description'])) {
            $description = $this->fields[$columnName]['description'];
        } else {
            $description = '';
        }

        if ($description) {
            if ($translate) {
                return $this->_translate($description);
            } else {
                return $description;
            }
        } else {
            return null;
        }
    }

    /**
     * Additionally handles validators (adds/removes options if needed).
     *
     * @param string $field      Database field name
     * @param array  $validators List of custom validators
     * @param array  $dbRecord   Entity record
     *
     * @return void
     */
    private function _prepareValidators($field, $validators, $dbRecord)
    {
        if (is_array($validators)) {
            foreach ($validators as $i => &$validator) {
                // Validation options provided
                if (is_array($validator)) {
                    // Add exclusion when validating existing value
                    if ($validator[0] == 'Db_NoRecordExists') {
                        if ($this->getRequest()->getActionName() == self::ACTION_UPDATE) {
                            $validator[2]['exclude'] = array('field' => $field, 'value' => $dbRecord[$field]);
                        }
                    }
                }
            }
        } else {
            $validators = array();
        }

        return $validators;
    }

    /**
     * Builds the edition form object. Use this method to apply custom logic like decorators etc.
     *
     * @param array &$form Form configuration array
     *
     * @return Zend_Form Instance of Zend_Form
     */
    protected function prepareEditForm(array &$form)
    {
        $formObject = new Shark_Form($form);

        // Add required flag
        foreach ($formObject->getElements() as $element) {
            $label = $element->getDecorator('Label');
            if (is_object($label)) {
                $label->setOption('requiredSuffix', ' *');
            }

            // Override default form decorator for certain elements that cause spaces
            if ($element instanceof Zend_Form_Element_Button
                || $element instanceof Zend_Form_Element_Submit
                || $element instanceof Zend_Form_Element_Hash
                || $element instanceof Zend_Form_Element_Hidden
            ) {
                $element->setDecorators(array('ViewHelper'));
            }
        }

        $formObject->setAttrib('class', self::CSS_ID . '-edit-form');

        return $formObject;
    }

    /**
     * Builds the search form object. Use this method to apply custom logic like decorators etc.
     *
     * @param array &$form form configuration array
     *
     * @return Zend_Form instance of Zend_Form
     */
    protected function prepareSearchForm(array &$form)
    {
        $formObject = new Shark_Form($form);
        $formObject->setLegend('SHARK_SCAFFOLD_SEARCH_LEGEND');
        $formObject->setAttrib('class', 'form form-inline pull-left');
        $formObject->setDecorators(
            array(
                'FormElements',
                array('HtmlTag', array('tag' => 'div', 'class' => 'pull-left')),
                'Fieldset',
                'Form',
            )
        );
        $formObject->setElementDecorators(
            array(
                'Label',
                'Errors',
                'ViewHelper',
            )
        );

        foreach ($formObject->getElements() as $element) {
            // Override default form decorator for certain elements that cause spaces
            if ($element instanceof Zend_Form_Element_Button
                || $element instanceof Zend_Form_Element_Submit
                || $element instanceof Zend_Form_Element_Hash
                || $element instanceof Zend_Form_Element_Hidden
            ) {
                $element->setDecorators(array('ViewHelper'));
            }
        }

        $formObject->addDisplayGroup(
            array(
                'submit',
                'reset',
            ),
            'buttons',
            array(
                'decorators' => array(
                    'FormElements',
                    array('HtmlTag', array('tag' => 'div', 'class' => 'form-actions')),
                ),
            )
        );

        return $formObject;
    }

    /**
     * Allows to initialize a JavaScript date picker.
     * Typically you should include here necessary JS files.
     *
     * @param array $fields fields that use date picking
     *
     * @return void
     */
    protected function loadDatePicker(array $fields)
    {
    }

    /**
     * Allows to initialize a JavaScript rich text editor.
     * Typically you should include here necessary JS files.
     *
     * @param array $fields fields that use rich text editor
     *
     * @return void
     */
    protected function loadRichTextEditor(array $fields)
    {
    }

    /**
     * The function called every time BEFORE entity is created.
     *
     * @param Zend_Form $form        Submitted form object
     * @param array     &$formValues Form values.
     *
     * @return boolean True if creation must happen or false otherwise
     */
    protected function beforeCreate(Zend_Form $form, array &$formValues)
    {
        return true;
    }

    /**
     * The function called every time AFTER entity has been created.
     *
     * @param Zend_Form $form     Submitted form object
     * @param int       $insertId Just inserted entity's id
     *
     * @return boolean True if automatic redirect must happen and false if user will redirect manually.
     */
    protected function afterCreate(Zend_Form $form, $insertId)
    {
        return true;
    }

    /**
     * The function called every time BEFORE entity is updated.
     *
     * @param Zend_Form $form        Submitted form object
     * @param array     &$formValues Values as returned by getDbValuesUpdate method
     *
     * @return boolean True if update must happen or false otherwise
     */
    protected function beforeUpdate(Zend_Form $form, array &$formValues)
    {
        return true;
    }

    /**
     * The function called every time AFTER entity has been updated.
     *
     * @param Zend_Form $form Submitted form object
     * @param int       $id   Row id.
     *
     * @return boolean True if automatic redirect must happen and false if user will redirect manually
     */
    protected function afterUpdate(Zend_Form $form, $id)
    {
        return true;
    }

    /**
     * The function called every time BEFORE entity is deleted.
     *
     * @param Zend_Db_Table_Row_Abstract $entity Record to be deleted
     *
     * @return boolean true if deletion must happen or false otherwise
     */
    protected function beforeDelete(Zend_Db_Table_Row_Abstract $entity)
    {
        return true;
    }

    /**
     * The function called every time AFTER entity has been deleted.
     *
     * @param Zend_Db_Table_Row_Abstract $entity the deleted record
     *
     * @return boolean true if automatic redirect must happen and false if user will redirect manually
     */
    protected function afterDelete(Zend_Db_Table_Row_Abstract $entity)
    {
        return true;
    }

    /**
     * Creates an IMG tag with the image value.
     *
     * @param mixed $item Current item.
     *
     * @return string The IMG tag.
     */
    protected function createImage($item)
    {
        if (isset($item->image)) {
            $title = isset($item->title) ? $item->title : '';
            $image = '<img src="' . $this->view->baseUrl($item->image) . '" alt="' . $title . '" width="60" />';
            return $image;
        }
        return $item->image;
    }

    /**
     * Displays HTML content as text using strip_tags.
     *
     * @param mixed   $item     Current item.
     * @param string  $replace  Text to replace.
     * @param boolean $truncate Whether to truncate the text or not.
     * @param int     $limit    Text limit.
     *
     * @return string Striped text.
     */
    protected function displayHtmlAsText($item, $replace = 'description', $truncate = true, $limit = 100)
    {
        if (isset($item->$replace)) {
            $text = strip_tags($item->$replace);
            if ($truncate) {
                $text = $this->_truncate($text, $limit);
            }
            return $text;
        }
        return $item->$replace;
    }

    /**
     * Creates an IMG tag with the thumbnail value.
     *
     * @param mixed $item Current item.
     *
     * @return string The IMG tag.
     */
    protected function createThumbnail($item)
    {
        if (isset($item->thumbnail)) {
            $title = isset($item->title) ? $item->title : '';
            $image = '<img src="' . $this->view->baseUrl($item->thumbnail) . '" alt="' . $title . '" />';
            return $image;
        }
        return $item->thumbnail;
    }

    /**
     * Translates a string using a translator, or returns original if none was defined.
     *
     * @param string $string original string
     *
     * @return string translated string
     */
    private function _translate($string)
    {
        if (isset($this->_options['translator'])) {
            return $this->_options['translator']->translate($string);
        }

        return $string;
    }

    /**
     * Sorts fields for listing.
     *
     * @param array $a First group of Fields.
     * @param array $b Second group of fields.
     *
     * @return int
     */
    private function _sortByListOrder($a, $b)
    {
        if (!isset($a['listOrder'])) {
            $a['listOrder'] = $a['order'];
        }

        if (!isset($b['listOrder'])) {
            $b['listOrder'] = $b['order'];
        }

        return $a['listOrder'] - $b['listOrder'];
    }

    /**
     * Sorts fields for listing.
     *
     * @param array $a First group of fields.
     * @param array $b Second group of fields.
     *
     * @return int
     */
    private function _sortByEditOrder($a, $b)
    {
        if (!isset($a['editOrder'])) {
            $a['editOrder'] = $a['order'];
        }

        if (!isset($b['editOrder'])) {
            $b['editOrder'] = $b['order'];
        }

        return $a['editOrder'] - $b['editOrder'];
    }

    /**
     * Removes elements that must be skipped from listing.
     *
     * @param array $value Remove hidden element?
     *
     * @return boolean
     */
    private function _removeHiddenListItems($value)
    {
        if (isset($value['hide'])) {
            $hide = $value['hide'];
            if ($hide === true) {
                return false;
            }
            if (is_string($hide)) {
                $hide = explode(',', $hide);
            }
            if (in_array('list', $hide)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Truncate text.
     *
     * Original PHP code by Chirp Internet: www.chirp.com.au
     * Please acknowledge use of this code by including this header.
     *
     * @param string $string Text to be truncated.
     * @param int    $limit  Text limit.
     * @param string $break  Break at this character.
     * @param string $ending Text to add at the end.
     *
     * @return string Truncated text.
     */
    private function _truncate($string, $limit, $break = '.', $ending = '...')
    {
        // return with no change if string is shorter than $limit
        if(strlen($string) <= $limit) return $string;

        // is $break present between $limit and the end of the string?
        if (false !== ($breakpoint = strpos($string, $break, $limit))) {
            if ($breakpoint < strlen($string) - 1) {
                $string = substr($string, 0, $breakpoint) . $ending;
            }
        }
        return $string;
    }
}