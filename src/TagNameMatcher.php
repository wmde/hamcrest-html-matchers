<?php

namespace WMDE\HamcrestHtml;

use Hamcrest\Description;
use Hamcrest\Matcher;

class TagNameMatcher extends TagMatcher {

	/**
	 * @var Matcher|string
	 */
	private $tagNameMatcher;

	/**
	 * @param Matcher|string $tagName
	 *
	 * @return self
	 */
	public static function withTagName( $tagName ) {
		return new static( $tagName );
	}

	/**
	 * @param Matcher|string $tagNameMatcher
	 */
	public function __construct( $tagNameMatcher ) {
		parent::__construct();
		$this->tagNameMatcher = $tagNameMatcher;
	}

	public function describeTo( Description $description ) {
		$description->appendText( 'with tag name ' );
		if ( $this->tagNameMatcher instanceof Matcher ) {
			$description->appendDescriptionOf( $this->tagNameMatcher );
		} else {
			$description->appendValue( $this->tagNameMatcher );
		}
	}

	/**
	 * @param \DOMElement $item
	 * @param Description $mismatchDescription
	 *
	 * @return bool
	 */
	protected function matchesSafelyWithDiagnosticDescription( $item, Description $mismatchDescription ) {
		if ( $this->tagNameMatcher instanceof Matcher ) {
			if ( $this->tagNameMatcher->matches( $item->tagName ) ) {
				return true;
			}
		} else {
			if ( $item->tagName === $this->tagNameMatcher ) {
				return true;
			}
		}

		$mismatchDescription->appendText( 'tag name ' );
		if ( $this->tagNameMatcher instanceof Matcher ) {
			$this->tagNameMatcher->describeMismatch( $item->tagName, $mismatchDescription );
		} else {
			$mismatchDescription->appendText( 'was ' )->appendValue( $item->tagName );
		}

		return false;
	}

}
