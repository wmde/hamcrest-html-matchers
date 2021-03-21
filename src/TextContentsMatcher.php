<?php

namespace WMDE\HamcrestHtml;

use Hamcrest\Description;
use Hamcrest\Matcher;

class TextContentsMatcher extends TagMatcher {

	/**
	 * @var Matcher|string
	 */
	private $matcher;

	/**
	 * @param Matcher|string $text
	 *
	 * @return self
	 */
	public static function havingTextContents( $text ) {
		return new static( $text );
	}

	/**
	 * @param Matcher|string $matcher
	 */
	public function __construct( $matcher ) {
		parent::__construct();
		$this->matcher = $matcher;
	}

	public function describeTo( Description $description ) {
		$description->appendText( 'having text contents ' );
		if ( $this->matcher instanceof Matcher ) {
			$description->appendDescriptionOf( $this->matcher );
		} else {
			$description->appendValue( $this->matcher );
		}
	}

	/**
	 * @param \DOMElement $item
	 * @param Description $mismatchDescription
	 *
	 * @return bool
	 */
	protected function matchesSafelyWithDiagnosticDescription( $item, Description $mismatchDescription ) {
		if ( $this->matcher instanceof Matcher ) {
			return $this->matcher->matches( $item->textContent );
		} else {
			return $item->textContent === (string)$this->matcher;
		}
	}

}
