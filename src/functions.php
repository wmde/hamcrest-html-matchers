<?php
use Hamcrest\Matcher;
use Hamcrest\Util;

if (!function_exists('htmlPiece')) {
    /**
     * @param mixed $elementMatcher
     *
     * @return \WMDE\HamcrestHtml\HtmlMatcher
     */
    function htmlPiece($elementMatcher = null) {
        return \WMDE\HamcrestHtml\HtmlMatcher::htmlPiece($elementMatcher);
    }
}

if (!function_exists('havingRootElement')) {
    function havingRootElement(\WMDE\HamcrestHtml\TagMatcher $matcher = null) {
        return new \WMDE\HamcrestHtml\RootElementMatcher($matcher);
    }
}

if (!function_exists('havingDirectChild')) {
    function havingDirectChild($elementMatcher = null) {
        return new \WMDE\HamcrestHtml\DirectChildElementMatcher($elementMatcher);
    }
}

if (!function_exists('havingChild')) {
    function havingChild($elementMatcher = null) {
        return new \WMDE\HamcrestHtml\ChildElementMatcher($elementMatcher);
    }
}

if (!function_exists('withTagName')) {
    /**
     * @param Matcher|string $tagName
     *
     * @return \WMDE\HamcrestHtml\TagNameMatcher
     */
    function withTagName($tagName) {
        return new \WMDE\HamcrestHtml\TagNameMatcher(Util::wrapValueWithIsEqual($tagName));
    }
}

if (!function_exists('withAttribute')) {
    /**
     * @param Matcher|string $attributeName
     *
     * @return \WMDE\HamcrestHtml\AttributeMatcher
     */
    function withAttribute($attributeName) {
        return new \WMDE\HamcrestHtml\AttributeMatcher(
            Util::wrapValueWithIsEqual($attributeName)
        );
    }
}

if (!function_exists('')) {
    /**
     * @param Matcher|string $class
     *
     * @return \WMDE\HamcrestHtml\ClassMatcher
     */
    function withClass($class) {
        //TODO don't allow to call with empty string

        return new \WMDE\HamcrestHtml\ClassMatcher(Util::wrapValueWithIsEqual($class));
    }
}

if (!function_exists('havingTextContents')) {
    /**
     * @param Matcher|string $text
     *
     * @return \WMDE\HamcrestHtml\TextContentsMatcher
     */
    function havingTextContents($text) {
        return new \WMDE\HamcrestHtml\TextContentsMatcher(Util::wrapValueWithIsEqual($text));
    }
}

if (!function_exists('tagMatchingOutline')) {
    /**
     * @param string $htmlOutline
     *
     * @return \WMDE\HamcrestHtml\ComplexTagMatcher
     */
    function tagMatchingOutline($htmlOutline) {
        return \WMDE\HamcrestHtml\ComplexTagMatcher::tagMatchingOutline($htmlOutline);
    }
}
