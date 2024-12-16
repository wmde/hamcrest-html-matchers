<?php

namespace WMDE\HamcrestHtml\Test;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Traversable;
use WMDE\HamcrestHtml\XmlNodeRecursiveIterator;

/**
 * @covers \WMDE\HamcrestHtml\XmlNodeRecursiveIterator
 */
class XmlNodeRecursiveIteratorTest extends TestCase {

	public function testIteratesAllElements_WhenFlatStructureGiven() {
		$DOMNodeList = $this->createDomNodeListFromHtml( '<p></p>' );

		$recursiveIterator = new XmlNodeRecursiveIterator( $DOMNodeList );

		$tagNames = $this->collectTagNames( $recursiveIterator );
		assertThat( $tagNames, is( equalTo( [ 'p' ] ) ) );
	}

	public function testIteratesElementsInAnyOrder_WhenNestedStructureGiven() {
		$DOMNodeList = $this->createDomNodeListFromHtml( '<a1><b1></b1><b2><c21></c21></b2></a1>' );

		$recursiveIterator = new XmlNodeRecursiveIterator( $DOMNodeList );

		$tagNames = $this->collectTagNames( $recursiveIterator );
		assertThat( $tagNames, is( containsInAnyOrder( 'a1', 'b1', 'b2', 'c21' ) ) );
	}

	private function createDomNodeListFromHtml( $html ) {
		$internalErrors = libxml_use_internal_errors( true );
		$DOMDocument = new \DOMDocument();

		// phpcs:ignore Generic.PHP.NoSilencedErrors
		if ( !@$DOMDocument->loadHTML( $html ) ) {
			throw new RuntimeException( 'Filed to parse HTML' );
		}

		libxml_clear_errors();
		libxml_use_internal_errors( $internalErrors );
		return $DOMDocument->documentElement->childNodes->item( 0 )->childNodes;
	}

	/**
	 * @param Traversable $recursiveIterator
	 * @return array
	 */
	protected function collectTagNames( $recursiveIterator ) {
		$array = iterator_to_array( $recursiveIterator );
		return array_map( static function ( \DOMElement $node ) {
			return $node->tagName;
		}, $array );
	}

}
