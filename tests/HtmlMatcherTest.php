<?php

namespace WMDE\HamcrestHtml\Test;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use WMDE\HamcrestHtml\HtmlMatcher;

/**
 * @covers \WMDE\HamcrestHtml\HtmlMatcher
 */
class HtmlMatcherTest extends TestCase {

	/**
	 * @dataProvider dataProvider_HtmlTagNamesIntroducedInHtml5
	 */
	public function testConsidersValidHtml_WhenUnknownForHtmlParserTagIsGiven( $tagIntroducedInHtml5 ) {
		$html = "<$tagIntroducedInHtml5></$tagIntroducedInHtml5>";

		assertThat( $html, is( HtmlMatcher::htmlPiece() ) );
	}

	public function dataProvider_HtmlTagNamesIntroducedInHtml5() {
		return [
			'article' => [ 'article' ],
			'aside' => [ 'aside' ],
			'bdi' => [ 'bdi' ],
			'details' => [ 'details' ],
			'dialog' => [ 'dialog' ],
			'figcaption' => [ 'figcaption' ],
			'figure' => [ 'figure' ],
			'footer' => [ 'footer' ],
			'header' => [ 'header' ],
			'main' => [ 'main' ],
			'mark' => [ 'mark' ],
			'menuitem' => [ 'menuitem' ],
			'meter' => [ 'meter' ],
			'nav' => [ 'nav' ],
			'progress' => [ 'progress' ],
			'rp' => [ 'rp' ],
			'rt' => [ 'rt' ],
			'ruby' => [ 'ruby' ],
			'section' => [ 'section' ],
			'summary' => [ 'summary' ],
			'time' => [ 'time' ],
			'wbr' => [ 'wbr' ],
			'datalist' => [ 'datalist' ],
			'keygen' => [ 'keygen' ],
			'output' => [ 'output' ],
			'canvas' => [ 'canvas' ],
			'svg' => [ 'svg' ],
			'audio' => [ 'audio' ],
			'embed' => [ 'embed' ],
			'source' => [ 'source' ],
			'track' => [ 'track' ],
			'video' => [ 'video' ],
		];
	}

	public function testConsidersValidHtml_WHtmlContainsScriptTagWithHtmlContents() {
		$html = "<div>
<script type='x-template'>
	<span></span>
</script>
</div>";

		assertThat( $html, is( HtmlMatcher::htmlPiece() ) );
	}

	public function testAddsSpecificTextInsideTheScriptTagsInsteadOfItsContents() {
		$html = "<div>
<script type='x-template'><span></span></script>
</div>";

		assertThat( $html, is( htmlPiece( havingChild(
			both( withTagName( 'script' ) )
				->andAlso( havingTextContents( "<span><\\/span>" ) ) ) ) ) );
	}

	public function testDoesNotTouchScriptTagAttributes() {
		$html = "<div>
<script type='x-template' attr1='value1'>
	<span></span>
</script>
</div>";

		assertThat( $html, is( htmlPiece( havingChild(
			allOf(
				withTagName( 'script' ),
				withAttribute( 'type' )->havingValue( 'x-template' ),
				withAttribute( 'attr1' )->havingValue( 'value1' )
			) ) ) ) );
	}

	public function testConsidersValidHtml_WhenUnrelatedXMLErrors() {
		libxml_use_internal_errors( true );
		$document = new DOMDocument();
		$document->loadHTML( 'ThisIsNoHTML<' );

		assertThat( '<html></html>', is( HtmlMatcher::htmlPiece() ) );
	}

}
