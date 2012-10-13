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
 * @subpackage Validators
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
/**
 * Password confirmation validator.
 *
 * @category   Shark
 * @package    Admin
 * @subpackage Validators
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
class Admin_Validate_PasswordConfirm extends Zend_Validate_Abstract
{
    const NOT_MATCH = 'notMatch';

    /**
     * @var array $_messageTemplates Message templates.
     */
    // @codingStandardsIgnoreStart
    protected $_messageTemplates = array(
        self::NOT_MATCH => 'SHARK_VALIDATE_PASSWORDCONFIRM_NOT_MATCH',
    );
    // @codingStandardsIgnoreEnd

    /**
     * Checks if password confirmation is valid.
     *
     * @param mixed $value   Current value.
     * @param mixed $context Current context.
     *
     * @return boolean
     */
    public function isValid($value, $context = null)
    {
        $value = (string)$value;
        $this->_setValue($value);
        if (is_array($context)) {
            if (isset($context['password_confirm'])
                && ($value == $context['password_confirm'])
            ) {
                return true;
            }
        } else if (is_string($context) && $value == $context) {
            return true;
        }
        $this->_error(self::NOT_MATCH);
        return false;
    }
}