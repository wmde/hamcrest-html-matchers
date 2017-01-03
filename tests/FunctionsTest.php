<?php

namespace Bekh6ex\HamcrestHtml\Test;

use Hamcrest\AssertionError;
use Hamcrest\Matcher;

class FunctionsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function havingRootElement_MultipleRootTags_ThrowsException()
    {
        //TODO Does it make sence?
        $html = '<p></p><p></p>';

        $this->expectException(AssertionError::class);
        assertThat($html, is(htmlPiece(havingRootElement())));
    }

    /**
     * @test
     * @dataProvider dataProvider_ElementExists
     */
    public function matcherCanFindElement($html, $matcher) {
        assertThat($html, is(htmlPiece($matcher)));
    }

    /**
     * @test
     * @dataProvider dataProvider_ElementDoesNotExist
     */
    public function matcherCantFindElement($html, $matcher, Matcher $messageMatcher) {
        try {
            assertThat($html, is(htmlPiece($matcher)));
        } catch (AssertionError $e) {
            assertThat($e->getMessage(), $messageMatcher);
            return;
        }

        $this->fail("Didn't catch expected exception");
    }

    public function dataProvider_ElementExists()
    {
        return [
            'htmlPiece - simple case' => [
                '<p></p>',
                null
            ],
            'havingRootElement - has root element' => [
                '<p></p>',
                havingRootElement()
            ],
            'withTagName - simple case' => [
                '<p><b></b></p>',
                havingRootElement(withTagName('p'))
            ],
            'havingDirectChild - without qualifier' => [
                '<p></p>',
                havingDirectChild()
            ],
            'havingDirectChild - nested structure' => [
                '<p><b></b></p>',
                havingDirectChild(havingDirectChild(withTagName('b')))
            ],
            'havingChild - target tag is not first' => [
                '<i></i><b></b>',
                havingChild(withTagName('b'))
            ],
            'havingChild - target tag is nested' => [
                '<p><b><i></i></b></p>',
                havingChild(withTagName('i'))
            ],
        ];
    }

    public function dataProvider_ElementDoesNotExist()
    {
        return [
            //TODO add empty string case
            'htmlPiece - messed up tags' => [
                '<p><a></p></a>',
                null,
                allOf(containsString('html piece'), containsString('there was parsing error'))
            ],
            'withTagName - simple case' => [
                '<p><b></b></p>',
                havingRootElement(withTagName('b')),
                allOf(containsString('having root element'), containsString('with tag name "b"'), containsString('root element tag name was "p"')),
            ],
            'havingDirectChild - no direct child' => [
                '<p></p>',
                havingDirectChild(havingDirectChild()),
                allOf(containsString('having direct child'), containsString('with direct child with no direct children')),
            ],
            'havingDirectChild - single element' => [
                '<p></p>',
                havingDirectChild(withTagName('b')),
                allOf(containsString('having direct child'), containsString('with tag name "b"')),
            ],
            'havingDirectChild - nested matcher' => [
                '<p><b></b></p>',
                havingDirectChild(havingDirectChild(withTagName('p'))),
                containsString('TODO add text') //TODO add text
            ],
            'havingChild - target tag is absent' => [
                '<p><b><i></i></b></p>',
                havingChild(withTagName('br')),
                containsString('TODO add text') //TODO add text
            ],
        ];
    }
}
