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
 * @subpackage Version
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
/**
 * Framework version.
 *
 * @category   Library
 * @package    Shark
 * @subpackage Version
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
class Shark_Version
{
    /**
     * Shark Framework version.
     */
    const VERSION = '1.0';

    /**
     * @var string $_productName Product name
     */
    private static $_productName = 'Shark Web Intelligence v%s';

    /**
     * Gets the current framework version.
     *
     * @return string Current version.
     */
    public static function getProductName()
    {
        return sprintf(self::$_productName, self::VERSION);
    }

    /**
     * Compare the specified Shark Framework version string $version
     * with the current Shark_Version::VERSION of Shark Framework.
     *
     * @param string $version A version string (e.g. "0.7.1").
     *
     * @return int -1 If the $version is older, 0 if they are the same, and +1 if $version is newer.
     */
    public static function compareVersion($version)
    {
        $version = strtolower($version);
        $version = preg_replace('/(\d)pr(\d?)/', '$1a$2', $version);
        return version_compare($version, strtolower(self::VERSION));
    }
}