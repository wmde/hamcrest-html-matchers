<?php

namespace WMDE\HamcrestHtml;

use Hamcrest\Core\AllOf;
use Hamcrest\Core\IsEqual;
use InvalidArgumentException;
use Hamcrest\Description;
use Hamcrest\Matcher;

/**
 * @license GPL-2.0+
 */
class ComplexTagMatcher extends TagMatcher
{
    /**
     * @link http://www.xmlsoft.org/html/libxml-xmlerror.html#xmlParserErrors
     * @link https://github.com/Chronic-Dev/libxml2/blob/683f296a905710ff285c28b8644ef3a3d8be9486/include/libxml/xmlerror.h#L257
     */
    const XML_UNKNOWN_TAG_ERROR_CODE = 801;

    /**
     * @var string
     */
    private $tagHtmlRepresentation;

    /**
     * @var Matcher
     */
    private $matcher;

    /**
     * @param $tagHtmlRepresentation
     * @return self
     */
    public static function tagMatching($tagHtmlRepresentation)
    {
        return new self($tagHtmlRepresentation);
    }

    public function __construct($tagHtmlRepresentation)
    {
        parent::__construct();

        $this->tagHtmlRepresentation = $tagHtmlRepresentation;
        $this->matcher = $this->createMatcherFromHtml($tagHtmlRepresentation);
    }

    /**
     * Generates a description of the object.  The description may be part
     * of a description of a larger object of which this is just a component,
     * so it should be worded appropriately.
     *
     * @param \Hamcrest\Description $description
     *   The description to be built or appended to.
     */
    public function describeTo(Description $description)
    {
        // TODO: Implement describeTo() method.
    }

    /**
     * Subclasses should implement these. The item will already have been checked for
     * the specific type.
     */
    protected function matchesSafelyWithDiagnosticDescription($item, Description $mismatchDescription)
    {
        return $this->matcher->matches($item);
    }

    private function createMatcherFromHtml($html)
    {
        $internalErrors = libxml_use_internal_errors(true);
        $document = new \DOMDocument();

        if (!@$document->loadHTML($html)) {
            throw new \InvalidArgumentException("There was some parsing error of `$html`");
        }

        $errors = libxml_get_errors();
        libxml_clear_errors();
        libxml_use_internal_errors($internalErrors);

        $result = true;
        /** @var \LibXMLError $error */
        foreach ($errors as $error) {
            if ($this->isUnknownTagError($error)) {
                continue;
            }

            throw new \InvalidArgumentException(
                'There was parsing error: ' . trim($error->message) . ' on line ' . $error->line
            );
        }

        $directChildren = iterator_to_array($document->documentElement->childNodes);

        $body = array_shift($directChildren);
        $directChildren = iterator_to_array($body->childNodes);

        if (count($directChildren) !== 1) {
            throw new InvalidArgumentException('Expected exacly 1 tag description, got ' . count($directChildren));
        }

        /** @var \DOMElement $targetTag */
        $targetTag = $directChildren[0];

        if ($targetTag->childNodes->length > 0) {
            throw new InvalidArgumentException('Nested elements are not allowed');
        }

        $matcher = new TagNameMatcher(IsEqual::equalTo($targetTag->tagName));

        $attributeMatchers = [];
        /** @var \DOMAttr $attribute */
        foreach ($targetTag->attributes as $attribute) {
            $attributeMatcher = new AttributeMatcher(IsEqual::equalTo($attribute->name));
            if (!$this->isBooleanAttribute($html, $attribute->name)) {
                $attributeMatcher = $attributeMatcher->havingValue(IsEqual::equalTo($attribute->value));
            }

            $attributeMatchers[] = $attributeMatcher;
        }

        return AllOf::allOf(
            new TagNameMatcher(IsEqual::equalTo($targetTag->tagName)),
            call_user_func_array([AllOf::class, 'allOf'], $attributeMatchers)
        );
    }

    private function isUnknownTagError(\LibXMLError $error)
    {
        return $error->code === self::XML_UNKNOWN_TAG_ERROR_CODE;
    }

    private function isBooleanAttribute($inputHtml, $attributeName)
    {
        $quotedName = preg_quote($attributeName, '/');

        $attributeHasValueAssigned = preg_match("/\b{$quotedName}\s*=/ui", $inputHtml);
        return !$attributeHasValueAssigned;
    }
}
