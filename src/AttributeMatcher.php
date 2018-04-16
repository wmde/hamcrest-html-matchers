<?php

namespace WMDE\HamcrestHtml;

use Hamcrest\Description;
use Hamcrest\Matcher;

class AttributeMatcher extends TagMatcher {

	/**
	 * @var Matcher|string
	 */
	private $attributeNameMatcher;

	/**
	 * @var Matcher|string|null
	 */
	private $valueMatcher;

	/**
	 * @param Matcher|string $attributeName
	 *
	 * @return self
	 */
	public static function withAttribute( $attributeName ) {
		return new static( $attributeName );
	}

	/**
	 * @param Matcher|string $attributeNameMatcher
	 */
	public function __construct( $attributeNameMatcher ) {
		parent::__construct();

		$this->attributeNameMatcher = $attributeNameMatcher;
	}

	/**
	 * @param Matcher|string $value
	 *
	 * @return AttributeMatcher
	 */
	public function havingValue( $value ) {
		// TODO: Throw exception if value is set
		$result = clone $this;
		$result->valueMatcher = $value;

		return $result;
	}

	public function describeTo( Description $description ) {
		$description->appendText( 'with attribute ' );
		if ( $this->attributeNameMatcher instanceof Matcher ) {
			$description->appendDescriptionOf( $this->attributeNameMatcher );
		} else {
			$description->appendValue( $this->attributeNameMatcher );
		}
		if ( $this->valueMatcher !== null ) {
			$description->appendText( ' having value ' );
			if ( $this->attributeNameMatcher instanceof Matcher ) {
				$description->appendDescriptionOf( $this->valueMatcher );
			} else {
				$description->appendValue( $this->valueMatcher );
			}
		}
	}

	/**
	 * @param \DOMElement $item
	 * @param Description $mismatchDescription
	 *
	 * @return bool
	 */
	protected function matchesSafelyWithDiagnosticDescription( $item, Description $mismatchDescription ) {
		if ( $this->valueMatcher === null ) {
			if ( $this->attributeNameMatcher instanceof Matcher ) {
				/** @var \DOMAttr $attribute */
				foreach ( $item->attributes as $attribute ) {
					if ( $this->attributeNameMatcher->matches( $attribute->name ) ) {
						return true;
					}
				}
			} else {
				return $item->hasAttribute( $this->attributeNameMatcher );
			}
		} else {
			if ( $this->attributeNameMatcher instanceof Matcher ) {
				if ( $this->valueMatcher instanceof Matcher ) {
					/** @var \DOMAttr $attribute */
					foreach ( $item->attributes as $attribute ) {
						if ( $this->attributeNameMatcher->matches( $attribute->name )
							&& $this->valueMatcher->matches( $attribute->value )
						) {
							return true;
						}
					}
				} else {
					/** @var \DOMAttr $attribute */
					foreach ( $item->attributes as $attribute ) {
						if ( $this->attributeNameMatcher->matches( $attribute->name )
							&& $attribute->value === $this->valueMatcher
						) {
							return true;
						}
					}
				}
			} else {
				if ( $this->valueMatcher instanceof Matcher ) {
					return $item->hasAttribute( $this->attributeNameMatcher )
						&& $this->valueMatcher->matches( $item->getAttribute( $this->attributeNameMatcher ) );
				} else {
					return $item->getAttribute( $this->attributeNameMatcher ) === $this->valueMatcher;
				}
			}
		}

		return false;
	}

}
