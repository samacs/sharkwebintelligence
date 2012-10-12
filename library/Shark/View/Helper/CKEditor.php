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
 * @subpackage View.Helpers
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
/**
 * CKEditor view helper.
 *
 * @category   Library
 * @package    Shark
 * @subpackage View.Helpers
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
class Shark_View_Helper_Ckeditor extends Zend_View_Helper_FormTextarea
{
    /**
    * Number of editor instances in the form.
    *
    * @var int
    */
    public static $instances = 0;

    /**
    * Generates a WYSIWYG textarea element.
    *
    * @param string|array $name    If a string, the element name.
    * @param mixed        $value   The element value.
    * @param array        $attribs Attributes for the element tag.
    *
    * @return string
    */
    public function ckeditor($name, $value = null, $attribs = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        $id = null;
        extract($info); // name, value, attribs, options, listsep, disable
        $xhtml = $this->formTextarea($name, $value, $attribs);
        self::$instances++;
        if (self::$instances == 1) {
            $this->view->inlineScript()->appendFile($this->view->baseUrl('/js/ckeditor/ckeditor.js'));
        }
        $config = array();
        $ckfinder = $this->view->baseUrl('/js/ckfinder/ckfinder.html');
        $uploader = $this->view->baseUrl('/js/ckfinder/core/connector/php/connector.php?command=QuickUpload');
        /**
        * TODO: Find error here.
        */
        /*
        $config['filebrowserImageBrowseUrl'] = $ckfinder . '?Type=Images';
        $config['filebrowserFlashBrowseUrl'] = $ckfinder . '?Type=Flash';
        $config['filebrowserUploadUrl'] = $uploader . '&type=Files&currentFolder=/images/';
        $config['filebrowserImageUploadUrl'] = $uploader . '&type=Images';
        $config['filebrowserFlashUploadUrl'] = $uploader . '&type=Flash';
        $config = array_merge(Shark_Config::getConfig()->ckeditor->config->toArray(), $config);
        */
        $this->view->inlineScript()->appendScript(
            '!function() {
            CKEDITOR.replace(\'' . $this->view->escape($id) . '\', ' . Zend_Json::encode($config) . ');
            }();'
        );
        return $xhtml;
    }
}
