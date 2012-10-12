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
 * @subpackage Filter.Word
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
/**
 * Converts UTF8 string to similar ASCII.
 *
 * Code: http://www.unexpectedit.com/php/php-clean-string-of-utf8-chars-convert-to-similar-ascii-char
 *
 * @category   Library
 * @package    Shark
 * @subpackage Filter.Word
 * @author     Saul Martinez <saul@sharkwebintelligence.com>
 * @copyright  2012 Shark Web Intelligence
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    ${CURRENT_VERSION}
 * @link       http://www.sharkwebintelligence.com
 */
class Shark_Filter_Word_Utf8ToAscii implements Zend_Filter_Interface
{
    /**
     * Returns an string clean of UTF8 characters. It will convert
     * them to a similar ASCII character www.unexpectedit.com.
     *
     * @param string $value Value to filter.
     *
     * @return string Converted string.
     */
    public function filter($value)
    {
        // 1) convert á ô => a o
        $value = preg_replace("/[áàâãªä]/u", "a", $value);
        $value = preg_replace("/[ÁÀÂÃÄ]/u", "A", $value);
        $value = preg_replace("/[ÍÌÎÏ]/u", "I", $value);
        $value = preg_replace("/[íìîï]/u", "i", $value);
        $value = preg_replace("/[éèêë]/u", "e", $value);
        $value = preg_replace("/[ÉÈÊË]/u", "E", $value);
        $value = preg_replace("/[óòôõºö]/u", "o", $value);
        $value = preg_replace("/[ÓÒÔÕÖ]/u", "O", $value);
        $value = preg_replace("/[úùûü]/u", "u", $value);
        $value = preg_replace("/[ÚÙÛÜ]/u", "U", $value);
        $value = preg_replace("/[’‘‹›‚]/u", "'", $value);
        $value = preg_replace("/[“”«»„]/u", '"', $value);
        $value = str_replace("–", "-", $value);
        $value = str_replace(" ", " ", $value);
        $value = str_replace("ç", "c", $value);
        $value = str_replace("Ç", "C", $value);
        $value = str_replace("ñ", "n", $value);
        $value = str_replace("Ñ", "N", $value);

        //2) Translation CP1252. &ndash; => -
        $trans = get_html_translation_table(HTML_ENTITIES);
        $trans[chr(130)] = '&sbquo;';    // Single Low-9 Quotation Mark
        $trans[chr(131)] = '&fnof;';    // Latin Small Letter F With Hook
        $trans[chr(132)] = '&bdquo;';    // Double Low-9 Quotation Mark
        $trans[chr(133)] = '&hellip;';    // Horizontal Ellipsis
        $trans[chr(134)] = '&dagger;';    // Dagger
        $trans[chr(135)] = '&Dagger;';    // Double Dagger
        $trans[chr(136)] = '&circ;';    // Modifier Letter Circumflex Accent
        $trans[chr(137)] = '&permil;';    // Per Mille Sign
        $trans[chr(138)] = '&Scaron;';    // Latin Capital Letter S With Caron
        $trans[chr(139)] = '&lsaquo;';    // Single Left-Pointing Angle Quotation Mark
        $trans[chr(140)] = '&OElig;';    // Latin Capital Ligature OE
        $trans[chr(145)] = '&lsquo;';    // Left Single Quotation Mark
        $trans[chr(146)] = '&rsquo;';    // Right Single Quotation Mark
        $trans[chr(147)] = '&ldquo;';    // Left Double Quotation Mark
        $trans[chr(148)] = '&rdquo;';    // Right Double Quotation Mark
        $trans[chr(149)] = '&bull;';    // Bullet
        $trans[chr(150)] = '&ndash;';    // En Dash
        $trans[chr(151)] = '&mdash;';    // Em Dash
        $trans[chr(152)] = '&tilde;';    // Small Tilde
        $trans[chr(153)] = '&trade;';    // Trade Mark Sign
        $trans[chr(154)] = '&scaron;';    // Latin Small Letter S With Caron
        $trans[chr(155)] = '&rsaquo;';    // Single Right-Pointing Angle Quotation Mark
        $trans[chr(156)] = '&oelig;';    // Latin Small Ligature OE
        $trans[chr(159)] = '&Yuml;';    // Latin Capital Letter Y With Diaeresis
        $trans['euro'] = '&euro;';    // euro currency symbol
        ksort($trans);

        foreach ($trans as $k => $v) {
            $value = str_replace($v, $k, $value);
        }

        // 3) remove <p>, <br/> ...
        $value = strip_tags($value);

        // 4) &amp; => & &quot; => '
        $value = html_entity_decode($value);

        // 5) remove Windows-1252 symbols like "TradeMark", "Euro"...
        $value = preg_replace('/[^(\x20-\x7F)]*/', '', $value);

        $targets=array('\r\n', '\n', '\r', '\t');
        $results=array(" ", " ", " ", "");
        $value = str_replace($targets, $results, $value);

        return $value;
    }
}