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
 * @package    Admin
 * @subpackage Controllers
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
/**
 * Blog posts controller.
 *
 * @category   Site
 * @package    Admin
 * @subpackage Controllers
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
class Admin_PostsController extends Shark_Controller_Action_Scaffold
{
    protected $model = 'Posts';

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
                'title' => 'BLOG_POSTS_FORM_ID_LABEL',
                'sort' => true,
                'hide' => 'create,edit',
            ),
            'category_id' => array(
                'title' => 'BLOG_POSTS_FORM_CATEGORY_LABEL',
                'required' => true,
                'sort' => true,
                'search' => true,
                'filters' => array(
                    'StripTags',
                    'StringTrim',
                ),
                'displayField' => 'category.title',
            ),
            'title' => array(
                'title' => 'BLOG_POSTS_FORM_TITLE_LABEL',
                'sort' => true,
                'search' => true,
                'required' => true,
                'filters' => array(
                    'StripTags',
                    'StringTrim',
                ),
            ),
            'alias' => array(
                'title' => 'BLOG_POSTS_FORM_ALIAS_LABEL',
                'required' => false,
                'filters' => array(
                    'StripTags',
                    'StringTrim',
                ),
                'hide' => 'list',
            ),
            'intro' => array(
                'title' => 'BLOG_POSTS_FORM_INTRO_LABEL',
                'required' => true,
                'search' => true,
                'filters' => array(
                    'StringTrim',
                ),
                'listModifier' => array($this, 'displayHtmlAsText'),
                'modifierParams' => array(
                    'intro',
                    true,
                    30,
                ),
                'fieldType' => 'ckeditor',
            ),
            'full' => array(
                'title' => 'BLOG_POSTS_FORM_FULL_LABEL',
                'required' => false,
                'search' => true,
                'filters' => array(
                    'StringTrim',
                ),
                'hide' => 'list',
                'fieldType' => 'ckeditor',
            ),
            'user_id' => array(
                'title' => 'BLOG_POSTS_FORM_AUTHOR_LABEL',
                'sort' => true,
                'search' => true,
                'hide' => 'create,edit',
                'displayField' => 'author.name',
            ),
            'is_active' => array(
                'title' => 'BLOG_POSTS_FORM_IS_ACTIVE_LABEL',
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
            'show_readmore' => array(
                'title' => 'BLOG_POSTS_FORM_SHOW_READMORE_LABEL',
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
            'entityTitle' => 'BLOG_POSTS_ENTITY_NAME',
        );
        $this->scaffold($this->getModel(), $fields, $options);
    }

    /**
     * Gets a document for a blog post.
     *
     * @param int $id Post id.
     *
     * @return Shark_Search_Document_Post
     */
    private function _getPostDocument($id)
    {
        $post = $this->getModel()->find($id)->current();
        $created = new Zend_Date($post->created_at, 'es_MX');
        $modified = new Zend_Date($post->modified_at, 'es_MX');
        $year = $created->toString(Zend_Date::YEAR);
        $month = $created->toString(Zend_Date::MONTH);
        $day = $created->toString(Zend_Date::DAY);
        $category = $this->getModel('Categories')->find($post->category_id)->current()->alias;
        $author = $this->getModel('Users')->find($post->user_id)->current()->name;
        $alias = $post->alias;
        $url = sprintf('/blog/%s/%d/%d/%d/%s', $category, $year, $month, $day, $alias);
        $format = sprintf(
            '%s, %s %s %s',
            Zend_Date::WEEKDAY,
            Zend_Date::DAY,
            Zend_Date::MONTH_NAME,
            Zend_Date::YEAR
        );
        $created = $created->toString();
        if ($modified) {
            $modified = $modified->toString();
        }
        $document = new stdClass();
        $document->document_id = $id;
        $document->url = $url;
        $document->created = $created;
        $document->title = $post->title;
        $document->intro = strip_tags($post->intro);
        $document->author = $author;
        $document->content = strip_tags($post->full);
        $document->modified = $modified;
        return new Shark_Search_Document_Post($document);
    }
}
