<?php

namespace WMDE\HamcrestHtml\Test;

class StringContainsIgnoringWhiteSpaceTest extends \PHPUnit\Framework\TestCase {

	/**
	 * @test
	 * @dataProvider dataProvider_containsStringIgnoringWhiteSpace
	 */
	public function containsStringIgnoringWhiteSpaceIgnoresTheWhiteSpace( $text, $substring ) {
		assertThat( $text, containsStringIgnoringWhiteSpace( $substring ) );
	}

	/**
	 * @test
	 */
	public function containsStringIgnoringWhiteSpaceHandlesTextWithoutSubstring() {
		assertThat( 'this is some other text', not( containsStringIgnoringWhiteSpace( 'some text' ) ) );
	}

	/**
	 * @test
	 */
	public function containsStringIgnoringWhiteSpaceProducesReadableOutputOnError() {
		try {
			assertThat( ' text actual ', containsStringIgnoringWhiteSpace( ' text expected ' ) );
			$this->fail( 'AssertionError is expected to be thrown' );
		} catch ( \Hamcrest\AssertionError $e ) {
			assertThat(
				$e->getMessage(),
				both( containsString( 'Expected: a string containing ignoring whitespace " text expected "' ) )
					->andAlso( containsString( 'but: was " text actual "' ) )
			);
		}
	}

	public function dataProvider_containsStringIgnoringWhiteSpace() {
		return [
			'containsStringIgnoringWhiteSpace - exact substring is consider contained' => [
				'this is some text',
				'some text'
			],
			'containsStringIgnoringWhiteSpace - multiple spaces are ignored' => [
				'this is some  text',
				'some text'
			],
			'containsStringIgnoringWhiteSpace - tabs are considered equal to a space' => [
				"this is some\ttext",
				'some text'
			],
			'containsStringIgnoringWhiteSpace - line breaks are considered equal to a space' => [
				"this is some\ntext",
				'some text'
			],
			'containsStringIgnoringWhiteSpace - multiple line breaks are ignored' => [
				'this is
                    some
                    text',
				'some text'
			],
			'containsStringIgnoringWhiteSpace - multiple spaces are ignored in the pattern' => [
				'this is some text',
				'some  text'
			],
			'containsStringIgnoringWhiteSpace - leading and trailing spaces are ignored in the pattern' => [
				'this is some text',
				' some text '
			],
		];
	}

}
