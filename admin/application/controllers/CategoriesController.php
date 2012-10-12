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
 * @category   Shark
 * @package    Admin
 * @subpackage Controllers
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
/**
 * Blog controller
 *
 * @category   Shark
 * @package    Admin
 * @subpackage Controllers
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
class Admin_CategoriesController extends Shark_Controller_Action_Scaffold
{
    protected $model = 'Categories';

    /**
     * Initialize scaffolding.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $fields = array(
            'id' => array(
                'title' => 'BLOG_CATEGORIES_FORM_ID_LABEL',
                'sort' => true,
                'hide' => 'create,edit',
            ),
            'title' => array(
                'title' => 'BLOG_CATEGORIES_FORM_TITLE_LABEL',
                'required' => true,
                'search' => true,
                'sort' => true,
                'filters' => array(
                    'StripTags',
                    'StringTrim',
                ),
            ),
            'alias' => array(
                'title' => 'BLOG_CATEGORIES_FORM_ALIAS_LABEL',
                'required' => false,
                'filters' => array(
                    'StripTags',
                    'StringTrim',
                ),
                'hide' => 'list',
            ),
            'description' => array(
                'title' => 'BLOG_CATEGORIES_FORM_DESCRIPTION_LABEL',
                'required' => false,
                'search' => true,
                'filters' => array(
                    'StringTrim',
                ),
                'fieldType' => 'ckeditor',
            ),
            'is_active' => array(
                'title' => 'BLOG_CATEGORIES_FORM_IS_ACTIVE_LABEL',
                'sort' => true,
                'validators' => array(
                    array(
                        'InArray',
                        false,
                        array(
                            'SHARK_YES' => 1,
                            'SHARK_NO' => 0,
                        ),
                    ),
                ),
                'filters' => array(
                    'StripTags',
                    'StringTrim',
                ),
                'fieldType' => 'checkbox',
            ),
            'created_at' => array(
                'title' => 'SHARK_CREATED_AT',
                'hide' => 'create,edit',
                'sort' => true,
            ),
            'modified_at' => array(
                'title' => 'SHARK_MODIFIED_AT',
                'hide' => 'create,edit',
                'sort' => true,
            ),
        );
        $options = array(
            'entityTitle' => 'BLOG_CATEGORIES_ENTITY_NAME',
        );
        $this->scaffold($this->getModel(), $fields, $options);
    }
}