<?php

namespace WMDE\HamcrestHtml;

use Hamcrest\Description;
use Hamcrest\Matcher;
use Hamcrest\TypeSafeDiagnosingMatcher;

class ChildElementMatcher extends TypeSafeDiagnosingMatcher {

	/**
	 * @var Matcher|string|null
	 */
	private $matcher;

	/**
	 * @param Matcher|string|null $elementMatcher
	 *
	 * @return self
	 */
	public static function havingChild( $elementMatcher = null ) {
		return new static( $elementMatcher );
	}

	/**
	 * @param Matcher|string|null $matcher
	 */
	public function __construct( $matcher = null ) {
		parent::__construct( \DOMNode::class );
		$this->matcher = $matcher;
	}

	public function describeTo( Description $description ) {
		$description->appendText( 'having child ' );
		if ( $this->matcher ) {
			if ( $this->matcher instanceof Matcher ) {
				$description->appendDescriptionOf( $this->matcher );
			} else {
				$description->appendValue( $this->matcher );
			}
		}
	}

	/**
	 * @param \DOMDocument|\DOMNode $item
	 * @param Description $mismatchDescription
	 *
	 * @return bool
	 */
	protected function matchesSafelyWithDiagnosticDescription( $item, Description $mismatchDescription ) {
		if ( $item instanceof \DOMDocument ) {
			$item = $item->documentElement->childNodes->item( 0 );
			/** @var \DOMElement $item */
		}
		$directChildren = $item->childNodes;

		if ( $directChildren->length === 0 ) {
			$mismatchDescription->appendText( 'having no children' );
			return false;
		}

		if ( !$this->matcher ) {
			return true;
		}

		if ( $this->matcher instanceof Matcher ) {
			foreach ( new XmlNodeRecursiveIterator( $directChildren ) as $child ) {
				if ( $this->matcher->matches( $child ) ) {
					return true;
				}
			}
		} else {
			if ( $item->getElementsByTagName( $this->matcher )->length !== 0 ) {
				return true;
			}
		}

		$mismatchDescription->appendText( 'having no children ' )->appendDescriptionOf( $this->matcher );
		return false;
	}

}
