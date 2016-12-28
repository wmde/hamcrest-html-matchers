<?php

namespace Bekh6ex\HamcrestHtml\Test;

use Hamcrest\AssertionError;

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
    public function matcherCantFindElement($html, $matcher) {
        $this->expectException(AssertionError::class);
        assertThat($html, is(htmlPiece($matcher)));
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
                null
            ],
            'withTagName - simple case' => [
                '<p><b></b></p>',
                havingRootElement(withTagName('b'))
            ],
            'havingDirectChild - single element' => [
                '<p></p>',
                havingDirectChild(withTagName('b'))
            ],
            'havingDirectChild - nested matcher' => [
                '<p><b></b></p>',
                havingDirectChild(havingDirectChild(withTagName('p')))
            ],
            'havingChild - target tag is absent' => [
                '<p><b><i></i></b></p>',
                havingChild(withTagName('br'))
            ],
        ];
    }
}
