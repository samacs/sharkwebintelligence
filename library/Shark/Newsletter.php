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
 * @subpackage Newsletter
 * @author     Saul Martienz <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
/**
 * Newsletter handler.
 *
 * @category   Library
 * @package    Shark
 * @subpackage Newsletter
 * @author     Saul Martienz <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
class Shark_Newsletter
{
    /**
     * @var string $_string User name.
     */
    private $_username;

    /**
     * @var string $token User token.
     */
    private $_token;

    /**
     * @var string XMLREQUEST XML request format.
     */
    const XMLREQUEST = "<xmlrequest>
        <username>{USERNAME}</username>
        <usertoken>{TOKEN}</usertoken>
        <requesttype>{TYPE}</requesttype>
        <requestmethod>{METHOD}</requestmethod>
        <details>
            <emailaddress>{EMAIL}</emailaddress>
            <mailinglist>{LIST}</mailinglist>
            <format>{FORMAT}</format>
            <confirmed>yes</confirmed>
            <customfields>
                {CUSTOMFIELDS}
            </customfields>
        </details>
    </xmlrequest>";

    /**
     * Constructor.
     *
     * @param string $username User name.
     * @param string $token    User token.
     *
     * @return void
     */
    public function __construct($username, $token)
    {
        $this->_username = $username;
        $this->_token = $token;
    }

    /**
     * Suscribe a user to a mailing list.
     *
     * @param int    $listId Mailing list id.
     * @param string $name   User name.
     * @param string $email  User email.
     * @param string $format Format.
     *
     * @return boolean
     */
    public function suscribe($listId, $name, $email, $format = 'html')
    {
        $search = array(
            '{USERNAME}',
            '{TOKEN}',
            '{TYPE}',
            '{METHOD}',
            '{EMAIL}',
            '{LIST}',
            '{FORMAT}',
            '{CUSTOMFIELDS}',
        );
        $replace = array(
            $this->_username,
            $this->_token,
            'subscribers',
            'AddSubscriberToList',
            $email,
            $listId,
            $format,
            '<item>
                <fieldid>2</fieldid>
                <value>' . $name .'</value>
            </item>',
        );
        $config = Shark_Config::getConfig();

        $xml = str_replace($search, $replace, self::XMLREQUEST);

        $ch = curl_init($config->site->newsletter->endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $result = curl_exec($ch);
        if (!$result) {
            $notifier = new Shark_Email($config->site->email, $config->site->title, 'ATENCIÓN');
            $notifier->send('newsletter/subscriber-error', array('error' => $result));
            return false;
        }
        return true;
    }
}