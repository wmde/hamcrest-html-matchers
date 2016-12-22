<?php
use Hamcrest\Util;

/**
 * @return \Bekh6ex\HamcrestHtml\HtmlMatcher
 */
function htmlPiece(\Bekh6ex\HamcrestHtml\RootElementMatcher $elementMatcher = null) {
    return \Bekh6ex\HamcrestHtml\HtmlMatcher::htmlPiece($elementMatcher);
}

/**
 * @param \Bekh6ex\HamcrestHtml\TagMatcher $matcher
 * @return \Bekh6ex\HamcrestHtml\RootElementMatcher
 */
function havingRootElement(\Bekh6ex\HamcrestHtml\TagMatcher $matcher = null) {
    return new \Bekh6ex\HamcrestHtml\RootElementMatcher($matcher);
}

/**
 * @param string $tagName
 * @return \Bekh6ex\HamcrestHtml\TagNameMatcher
 */
function withTagName($tagName) {
    return new \Bekh6ex\HamcrestHtml\TagNameMatcher(Util::wrapValueWithIsEqual($tagName));
}
