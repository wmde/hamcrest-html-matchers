<?php

namespace WMDE\HamcrestHtml\Test;

class StringContainsIgnoringWhiteSpaceTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 * @dataProvider dataProvider_containsStringIgnoringWhiteSpace
	 */
	public function containsStringIgnoringWhiteSpaceIgnoresTheWhiteSpace($text, $substring) {
		assertThat($text, containsStringIgnoringWhiteSpace($substring));
	}

	/**
	 * @test
	 */
	public function containsStringIgnoringWhiteSpaceHandlesTextWithoutSubstring() {
		assertThat(not('this is some other text', containsStringIgnoringWhiteSpace('some text')));
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
		];
	}
}
