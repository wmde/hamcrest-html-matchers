<?php
use Hamcrest\Matcher;
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
 * @param Matcher|string $tagName
 * @return \WMDE\HamcrestHtml\TagNameMatcher
 */
function withTagName($tagName) {
    return new \WMDE\HamcrestHtml\TagNameMatcher(Util::wrapValueWithIsEqual($tagName));
}

/**
 * @param Matcher|string $attributeName
 * @return \WMDE\HamcrestHtml\AttributeMatcher
 */
function withAttribute($attributeName) {
    return new \WMDE\HamcrestHtml\AttributeMatcher(Util::wrapValueWithIsEqual($attributeName));
}

/**
 * @param Matcher|string $text
 * @return \WMDE\HamcrestHtml\TextContentsMatcher
 */
function havingTextContents($text) {
    return new \WMDE\HamcrestHtml\TextContentsMatcher(Util::wrapValueWithIsEqual($text));
}
