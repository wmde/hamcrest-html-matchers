<?php
use Hamcrest\Util;

/**
 * @return \WMDE\HamcrestHtml\HtmlMatcher
 */
function htmlPiece($elementMatcher = null) {
    return \WMDE\HamcrestHtml\HtmlMatcher::htmlPiece($elementMatcher);
}

/**
 * @param \WMDE\HamcrestHtml\TagMatcher $matcher
 * @return \WMDE\HamcrestHtml\RootElementMatcher
 */
function havingRootElement(\WMDE\HamcrestHtml\TagMatcher $matcher = null) {
    return new \WMDE\HamcrestHtml\RootElementMatcher($matcher);
}
function havingDirectChild($elementMatcher = null) {
    return new \WMDE\HamcrestHtml\DirectChildElementMatcher($elementMatcher);
}
function havingChild($elementMatcher = null) {
    return new \WMDE\HamcrestHtml\ChildElementMatcher($elementMatcher);
}


/**
 * @param string $tagName
 * @return \WMDE\HamcrestHtml\TagNameMatcher
 */
function withTagName($tagName) {
    return new \WMDE\HamcrestHtml\TagNameMatcher(Util::wrapValueWithIsEqual($tagName));
}

/**
 * @param $attributeName
 * @return \WMDE\HamcrestHtml\AttributeMatcher
 */
function withAttribute($attributeName) {
    return new \WMDE\HamcrestHtml\AttributeMatcher(Util::wrapValueWithIsEqual($attributeName));
}

function havingTextContents($text) {
    return new \WMDE\HamcrestHtml\TextContentsMatcher(Util::wrapValueWithIsEqual($text));
}
