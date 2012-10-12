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
 * @subpackage Form
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
/**
 * Form class.
 *
 * @category   Library
 * @package    Shark
 * @subpackage Form
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
abstract class Shark_Form_Abstract extends Zend_Form
{
    /**
     * Constructor
     *
     * Registers form view helper as decorator
     *
     * @param mixed $options Form options.
     *
     * @return void
     */
    public function __construct($options = null)
    {
        $this->addPrefixPath('Shark_Form_Element', 'Shark/Form/Element', 'element');
        $this->addElementPrefixPath('Shark_Filter', 'Shark/Filter', 'filter');
        $this->addElementPrefixPath('Shark_Validate', 'Shark/Validate', 'validate');
        parent::__construct($options);
    }
}