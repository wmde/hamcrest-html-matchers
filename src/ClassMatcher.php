<?php

namespace WMDE\HamcrestHtml;

use Hamcrest\Description;
use Hamcrest\Matcher;

class ClassMatcher extends TagMatcher {

	/**
	 * @var Matcher|string
	 */
	private $classMatcher;

	/**
	 * @param Matcher|string $class
	 *
	 * @return self
	 */
	public static function withClass( $class ) {
		return new static( $class );
	}

	/**
	 * @param Matcher|string $class
	 */
	public function __construct( $class ) {
		parent::__construct();
		$this->classMatcher = $class;
	}

	public function describeTo( Description $description ) {
		$description->appendText( 'with class ' );
		if ( $this->classMatcher instanceof Matcher ) {
			$description->appendDescriptionOf( $this->classMatcher );
		} else {
			$description->appendValue( $this->classMatcher );
		}
	}

	/**
	 * @param \DOMElement $item
	 * @param Description $mismatchDescription
	 *
	 * @return bool
	 */
	protected function matchesSafelyWithDiagnosticDescription( $item, Description $mismatchDescription ) {
		$classAttribute = $item->getAttribute( 'class' );

		if ( $this->classMatcher instanceof Matcher ) {
			$classes = preg_split( '/\s+/u', $classAttribute );
			foreach ( $classes as $class ) {
				if ( $this->classMatcher->matches( $class ) ) {
					return true;
				}
			}
			return false;
		} else {
			return (bool)preg_match(
				'/(^|\s)' . preg_quote( $this->classMatcher, '/' ) . '(\s|$)/u',
				$classAttribute
			);
		}
	}

}
