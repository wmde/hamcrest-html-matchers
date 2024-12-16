<?php

namespace WMDE\HamcrestHtml;

use Hamcrest\Text\SubstringMatcher;

class StringContainsIgnoringWhiteSpace extends SubstringMatcher {

	/**
	 * Matches if value is a string that contains $substring consider all whitespace as single space
	 *
	 * @param string $substring
	 *
	 * @return self
	 */
	public static function containsStringIgnoringWhiteSpace( $substring ) {
		return new self( $substring );
	}

	/**
	 * @param string $item
	 *
	 * @return bool
	 */
	protected function evalSubstringOf( $item ) {
		return strpos( $this->stripSpace( $item ), $this->stripSpace( $this->_substring ) ) !== false;
	}

	protected function relationship() {
		return 'containing ignoring whitespace';
	}

	/**
	 * @param string $string
	 *
	 * @return string
	 */
	private function stripSpace( $string ) {
		return trim( preg_replace( '/\s+/', ' ', $string ) );
	}

}
