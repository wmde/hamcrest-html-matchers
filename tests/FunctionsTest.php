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
            'htmlPiece - messed up tags' => [
                '<p><a></p></a>',
                null,
                allOf(containsString('html piece'), containsString('there was parsing error'))
            ],
            'withTagName - simple case' => [
                '<p><b></b></p>',
                havingRootElement(withTagName('b')),
                allOf(containsString('having root element'),
                    containsString('with tag name "b"'),
                    containsString('root element tag name was "p"')),
            ],
            'havingDirectChild - no direct child' => [
                '<p></p>',
                havingDirectChild(havingDirectChild()),
                allOf(containsString('having direct child'),
                    containsString('with direct child with no direct children')),
            ],
            'havingDirectChild - single element' => [
                '<p></p>',
                havingDirectChild(withTagName('b')),
                allOf(containsString('having direct child'),
                    containsString('with tag name "b"')),
            ],
            'havingDirectChild - nested matcher' => [
                '<p><b></b></p>',
                havingDirectChild(havingDirectChild(withTagName('p'))),
                both(containsString('having direct child having direct child with tag name "p"'))
                    ->andAlso(containsString('direct child with direct child tag name was "b"'))
            ],
            'havingChild - no children' => [
                '<p></p>',
                havingDirectChild(havingChild()),
                both(containsString('having direct child having child'))
                    ->andAlso(containsString('having no children'))
            ],
            'havingChild - target tag is absent' => [
                '<p><b><i></i></b></p>',
                havingChild(withTagName('br')),
                both(containsString('having child with tag name "br"'))
                    ->andAlso(containsString('having no children with tag name "br"'))
            ],
        ];
    }
}
