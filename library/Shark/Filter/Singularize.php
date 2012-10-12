<?php

class Shark_Filter_Singularize implements Zend_Filter_Interface
{

	public function filter($value)
	{
		$lowercasedValue = strtolower($value);
		foreach ($this->getUncountable() as $uncountable) {
			if (substr($lowercasedValue, (-1 * strlen($uncountable))) === $uncountable) {
				return $value;
			}
		}
		foreach ($this->getIrregular() as $plural => $singular) {
			if (preg_match('/(' . $singular . ')$/i', $value, $match)) {
				return preg_replace('/(' . $singular . ')$/i', substr($match[0], 0, 1) . substr($plural, 1), $value);
			}
		}
		foreach ($this->getSingular() as $pattern => $replacement) {
			if (preg_match($pattern, $value)) {
				return preg_replace($pattern, $replacement, $value);
			}
		}
		return $value;
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

	public function getSingular()
	{
		return array(
			'/(quiz)zes$/i' => '\1',
			'/(matr)ices$/i' => '\1ix',
			'/(vert|ind)ices$/i' => '\1ex',
			'/^(ox)en/i' => '\1',
			'/(alias|status)es$/i' => '\1',
			'/([octop|vir])i$/i' => '\1us',
			'/(cris|ax|test)es$/i' => '\1is',
			'/(shoe)s$/i' => '\1',
			'/(o)es$/i' => '\1',
			'/(bus)es$/i' => '\1',
			'/([m|l])ice$/i' => '\1ouse',
			'/(x|ch|ss|sh)es$/i' => '\1',
			'/(m)ovies$/i' => '\1ovie',
			'/(s)eries$/i' => '\1eries',
			'/([^aeiouy]|qu)ies$/i' => '\1y',
			'/([lr])ves$/i' => '\1f',
			'/(tive)s$/i' => '\1',
			'/(hive)s$/i' => '\1',
			'/([^f])ves$/i' => '\1fe',
			'/(^analy)ses$/i' => '\1sis',
			'/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => '\1\2sis',
			'/([ti])a$/i' => '\1um',
			'/(n)ews$/i' => '\1ews',
			'/s$/i' => '',
		);
	}
}