<?php

namespace WMDE\HamcrestHtml\Test;

use WMDE\HamcrestHtml\HtmlMatcher;

/**
 * @covers WMDE\HamcrestHtml\HtmlMatcher
 */
class HtmlMatcherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider dataProvider_HtmlTagNamesIntroducedInHtml5
     */
    public function considersValidHtml_WhenUnknownForHtmlParserTagIsGiven($tagIntroducedInHtml5)
    {
        $html = "<$tagIntroducedInHtml5></$tagIntroducedInHtml5>";

        assertThat($html, is(HtmlMatcher::htmlPiece()));
    }

    public function dataProvider_HtmlTagNamesIntroducedInHtml5()
    {
        return [
            'article' => ['article'],
            'aside' => ['aside'],
            'bdi' => ['bdi'],
            'details' => ['details'],
            'dialog' => ['dialog'],
            'figcaption' => ['figcaption'],
            'figure' => ['figure'],
            'footer' => ['footer'],
            'header' => ['header'],
            'main' => ['main'],
            'mark' => ['mark'],
            'menuitem' => ['menuitem'],
            'meter' => ['meter'],
            'nav' => ['nav'],
            'progress' => ['progress'],
            'rp' => ['rp'],
            'rt' => ['rt'],
            'ruby' => ['ruby'],
            'section' => ['section'],
            'summary' => ['summary'],
            'time' => ['time'],
            'wbr' => ['wbr'],
            'datalist' => ['datalist'],
            'keygen' => ['keygen'],
            'output' => ['output'],
            'canvas' => ['canvas'],
            'svg' => ['svg'],
            'audio' => ['audio'],
            'embed' => ['embed'],
            'source' => ['source'],
            'track' => ['track'],
            'video' => ['video'],
        ];
    }

}
