<?php

class Shark_Filter_Pluralize implements Zend_Filter_Interface
{

	public function filter($value)
	{
		$lowercasedValue = strtolower($value);
		foreach ($this->getUncountable() as $uncountable) {
			if (substr($lowercasedValue, (-1 * strlen($uncountable))) == $uncountable) {
				return $value;
			}
		}
		foreach ($this->getIrregular() as $plural => $singular) {
			if (preg_match('/(' . $plural . ')$/i', $value, $match)) {
				return preg_replace('/(' . $plural . ')$/i', substr($match[0], 0, 1) . substr($singular, 1), $value);
			}
		}
		foreach ($this->getPlural() as $pattern => $replacement) {
			if (preg_match($pattern, $value)) {
				return preg_replace($pattern, $replacement, $value);
			}
		}
		return false;
	}

	public function getPlural()
	{
		return array(
			'/(quiz)$/i' => '1zes',
			'/^(ox)$/i' => '1en',
			'/([m|l])ouse$/i' => '1ice',
			'/(matr|vert|ind)ix|ex$/i' => '1ices',
			'/(x|ch|ss|sh)$/i' => '1es',
			'/([^aeiouy]|qu)ies$/i' => '1y',
			'/([^aeiouy]|qu)y$/i' => '1ies',
			'/(hive)$/i' => '1s',
			'/(?:([^f])fe|([lr])f)$/i' => '12ves',
			'/sis$/i' => 'ses',
			'/([ti])um$/i' => '1a',
			'/(buffal|tomat)o$/i' => '1oes',
			'/(bu)s$/i' => '1ses',
			'/(alias|status)/i'=> '1es',
			'/(octop|vir)us$/i'=> '1i',
			'/(ax|test)is$/i'=> '1es',
			'/s$/i'=> 's',
			'/$/'=> 's'
		);
	}

	public function getUncountable()
	{
		return array(
			'equipment',
			'information',
			'rice',
			'money',
			'species',
			'series',
			'fish',
			'sheep',
		);
	}

	public function getIrregular()
	{
		return array(
			'person' => 'people',
			'man' => 'men',
			'child' => 'children',
			'sex' => 'sexes',
			'move' => 'moves',
		);
	}
}