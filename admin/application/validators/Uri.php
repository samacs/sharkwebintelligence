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
 * @subpackage Validate
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
/**
 * Validates URL.
 *
 * @category   Shark
 * @package    Admin
 * @subpackage Validate
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
class Admin_Validate_Uri extends Zend_Validate_Abstract
{
    const NOT_VALID = 'notValid';

    /**
     * @var array $_messageTemplates Message tempates.
     */
    // @codingStandardsIgnoreStart
    protected $_messageTemplates = array(
        self::NOT_VALID => 'SHARK_VALIDATE_URI_NOT_VALID',
    );
    // @codingStandardsIgnoreEnd

    /**
     * Checks if value is a valid URI.
     *
     * @param string $value Value to check.
     *
     * @return boolean
     */
    public function isValid($value)
    {
        $value = (string)$value;
        $this->_setValue($value);
        if (Zend_Uri::check($value)) {
            return true;
        }
        $this->_error(self::NOT_VALID);
        return false;
    }
}