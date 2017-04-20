<?php

namespace WMDE\HamcrestHtml;
use Hamcrest\Text\SubstringMatcher;

class StringContainsIgnoringWhiteSpace extends SubstringMatcher
{
	public function __construct($substring)
	{
		parent::__construct($this->_stripSpace($substring));
	}

	/**
	 * Matches if value is a string that contains $substring consider all whitespace as single space
	 */
	public static function containsStringIgnoringWhiteSpace($substring)
	{
		return new self($substring);
	}

	/**
	 * @param \DOMElement $item
	 *
	 * @return bool
	 */
	protected function evalSubstringOf($item)
	{
		return (false !== strpos($this->_stripSpace((string) $item), $this->_substring));
	}

	protected function relationship()
	{
		return 'containing';
	}

	/**
	 * Copied from IsEqualIgnoringWhiteSpace
	 *
	 * @param $string
	 *
	 * @return string
	 */
	private function _stripSpace($string)
	{
		$parts = preg_split("/[\r\n\t ]+/", $string);
		foreach ($parts as $i => $part) {
			$parts[$i] = trim($part, " \r\n\t");
		}

		return trim(implode(' ', $parts), " \r\n\t");
	}
}
