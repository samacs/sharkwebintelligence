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
 * Adds social share buttons.
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
class Shark_View_Helper_SocialShare extends Zend_View_Helper_Abstract
{
    private static $_scriptsSet = false;

    /**
     * Displays social sharing buttons using ShareThis.com
     *
     * @param array  $buttons List of buttons and properties.
     * @param string $size    Button size.
     *
     * @return string
     */
    public function socialShare($buttons, $size = 'large')
    {
        $publisher = Shark_Config::getConfig()->site->sharethis->publisher;
        $output = '';
        foreach ($buttons as $button) {
            $output .= '<span class="st_' . strtolower($button) . '_' . $size . '"';
            $output .= ' st_title="' . $this->view->headTitle() . '"';
            $output .= ' st_url="' . $this->view->absoluteUrl($this->view->url()) . '"';
            $output .= '></span>';
        }
        if (!self::$_scriptsSet) {
            $this->view->minifyInlineScript()->appendScript('var switchTo5x = true;');
            $this->view->minifyInlineScript()->appendFile('http://w.sharethis.com/button/buttons.js');
            $this->view->minifyInlineScript()->appendScript(
                'stLight.options({
                    publisher: "' . $publisher . '"
                });'
            );
            self::$_scriptsSet = true;
        }
        return $output;
    }
}