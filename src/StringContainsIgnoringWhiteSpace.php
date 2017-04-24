<?php

namespace WMDE\HamcrestHtml;

use Hamcrest\Text\SubstringMatcher;

class StringContainsIgnoringWhiteSpace extends SubstringMatcher
{
	/**
	 * Matches if value is a string that contains $substring consider all whitespace as single space
	 */
	public static function containsStringIgnoringWhiteSpace($substring)
	{
		return new self($substring);
	}

	/**
	 * @param string $item
	 *
	 * @return bool
	 */
	protected function evalSubstringOf($item)
	{
		return (false !== strpos($this->stripSpace((string) $item), $this->stripSpace($this->_substring)));
	}

	protected function relationship()
	{
		return 'containing ignoring whitespace';
	}

	/**
	 * @param $string
	 *
	 * @return string
	 */
	private function stripSpace( $string)
	{
		return trim(preg_replace( "/\s+/", ' ', $string));
	}
}
