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
 * @subpackage Search.Document
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
/**
 * Blog post document.
 *
 * @category   Library
 * @package    Shark
 * @subpackage Search.Document
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
class Shark_Search_Document_Post extends Zend_Search_Lucene_Document
{
    /**
     * Blog post document for search.
     *
     * @param object $document Blog document.
     *
     * @return void
     */
    public function __construct($document)
    {
        $this->addField(Zend_Search_Lucene_Field::Keyword('document_id', $document->document_id));
        $this->addField(Zend_Search_Lucene_Field::UnIndexed('url', $document->url));
        $this->addField(Zend_Search_Lucene_Field::UnIndexed('created', $document->created));
        $this->addField(Zend_Search_Lucene_Field::UnIndexed('modified', $document->modified));
        $this->addField(Zend_Search_Lucene_Field::UnIndexed('intro', $document->intro));
        $this->addField(Zend_Search_Lucene_Field::Text('title', $document->title));
        $this->addField(Zend_Search_Lucene_Field::Text('author', $document->author));
        $this->addField(Zend_Search_Lucene_Field::UnStored('content', $document->content));
    }
}