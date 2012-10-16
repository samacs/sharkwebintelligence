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
 * @package    Blog
 * @subpackage View.Helpers
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
/**
 * Shows the blog archive.
 *
 * @category   Site
 * @package    Blog
 * @subpackage View.Helpers
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
class Blog_View_Helper_BlogArchive extends Zend_View_Helper_Abstract
{
    /**
     * Get the blog archive.
     *
     * @param boolean $showPosts Whether to show the number of posts.
     *
     * @return string
     */
    public function blogArchive($showPosts = false)
    {
        $model = new Model_Posts();
        $blogArchive = $model->getArchive();
        $output = '<h3>Archivo</h3>';
        $output .= '<ul class="nav">';
        foreach ($blogArchive as $archive) {
            $output .= '<li>';
            $output .= '<a href="' . $this->view->url(array('year' => $archive['year']), 'blog') . '">';
            $output .= $archive['year'];
            if ($showPosts) {
                $output .= ' (' . $archive['posts'] . ')';
            }
            $output .= '</a>';
            $output .= '</li>';
        }
        $output .= '</ul>';
        return $output;
    }
}
