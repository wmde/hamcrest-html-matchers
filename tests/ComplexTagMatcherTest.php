<?php

namespace WMDE\HamcrestHtml\Test;

use Exception;
use Hamcrest\AssertionError;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ValueError;
use WMDE\HamcrestHtml\ComplexTagMatcher;

/**
 * @covers \WMDE\HamcrestHtml\ComplexTagMatcher
 */
class ComplexTagMatcherTest extends TestCase {

	public function testAssertPasses_WhenTagInHtmlHasSameTagName() {
		$html = '<p></p>';

		assertThat( $html, is( htmlPiece( havingChild(
			ComplexTagMatcher::tagMatchingOutline( '<p/>' )
		) ) ) );
	}

	public function testAssertFails_WhenTagInHtmlIsDiffersFromGivenTagName() {
		$html = '<a></a>';

		$this->expectException( AssertionError::class );
		assertThat( $html, is( htmlPiece( havingChild(
			ComplexTagMatcher::tagMatchingOutline( '<p/>' )
		) ) ) );
	}

	public function testCanNotCreateMatcherWithEmptyDescription() {
		if ( PHP_VERSION_ID > 80000 ) {
			$this->expectException( ValueError::class );
		} else {
			$this->expectException( InvalidArgumentException::class );
		}
		ComplexTagMatcher::tagMatchingOutline( '' );
	}

	public function testCanNotCreateMatcherExpectingTwoElements() {
		$this->expectException( Exception::class );
		ComplexTagMatcher::tagMatchingOutline( '<p></p><b></b>' );
	}

	public function testCanNotCreateMatcherWithChildElement() {
		$this->expectException( Exception::class );
		ComplexTagMatcher::tagMatchingOutline( '<p><b></b></p>' );
	}

	public function testAssertFails_WhenTagInHtmlDoesNotHaveExpectedAttribute() {
		$html = '<p></p>';

		$this->expectException( AssertionError::class );
		assertThat( $html, is( htmlPiece( havingChild(
			ComplexTagMatcher::tagMatchingOutline( '<p id="some-id"/>' ) ) ) ) );
	}

	public function testAssertPasses_WhenTagInHtmlHasExpectedAttribute() {
		$html = '<p id="some-id"></p>';

		assertThat( $html, is( htmlPiece( havingChild(
			ComplexTagMatcher::tagMatchingOutline( '<p id="some-id"/>' ) ) ) ) );
	}

	public function testAssertFails_WhenTagInHtmlDoesNotHaveAllExpectedAttribute() {
		$html = '<p id="some-id"></p>';

		$this->expectException( AssertionError::class );
		assertThat( $html, is( htmlPiece( havingChild(
			ComplexTagMatcher::tagMatchingOutline( '<p id="some-id" onclick="void();"/>' ) ) ) ) );
	}

	public function testAssertPasses_WhenExpectBooleanAttributeButItIsThereWithSomeValue() {
		$html = '<input required="anything">';

		assertThat( $html, is( htmlPiece( havingChild(
			ComplexTagMatcher::tagMatchingOutline( '<input required/>' ) ) ) ) );
	}

	public function testAssertFails_WhenExpectAttributeWithEmptyValueButItIsNotEmpty() {
		$html = '<input attr1="something">';

		$this->expectException( AssertionError::class );
		assertThat( $html, is( htmlPiece( havingChild(
			ComplexTagMatcher::tagMatchingOutline( '<input attr1=""/>' ) ) ) ) );
	}

	public function testAssertPasses_WhenGivenTagHasExpectedClass() {
		$html = '<input class="class1 class2">';

		assertThat( $html, is( htmlPiece( havingChild(
			ComplexTagMatcher::tagMatchingOutline( '<input class="class2"/>' ) ) ) ) );
	}

	public function testAssertFails_WhenGivenTagDoesNotHaveExpectedClass() {
		$html = '<input class="class1 class2">';

		$this->expectException( AssertionError::class );
		assertThat( $html, is( htmlPiece( havingChild(
			ComplexTagMatcher::tagMatchingOutline( '<input class="class3"/>' ) ) ) ) );
	}

	public function testToleratesExtraSpacesInClassDescription() {
		$html = '<input class="class1">';

		assertThat( $html, is( htmlPiece( havingChild(
			ComplexTagMatcher::tagMatchingOutline( '<input class="   class1   "/>' ) ) ) ) );
	}

}
