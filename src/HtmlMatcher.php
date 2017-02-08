<?php

namespace WMDE\HamcrestHtml;

use Hamcrest\Description;
use Hamcrest\DiagnosingMatcher;
use Hamcrest\Matcher;

class HtmlMatcher extends DiagnosingMatcher
{
    /**
     * @link http://www.xmlsoft.org/html/libxml-xmlerror.html#xmlParserErrors
     * @link https://github.com/Chronic-Dev/libxml2/blob/683f296a905710ff285c28b8644ef3a3d8be9486/include/libxml/xmlerror.h#L257
     */
    const XML_UNKNOWN_TAG_ERROR_CODE = 801;

    /**
     * @var RootElementMatcher
     */
    private $elementMatcher;

    /**
     * HtmlMatcher constructor.
     */
    public function __construct($elementMatcher = null)
    {
        $this->elementMatcher = $elementMatcher;
    }

    /**
     * @param string $html
     * @return HtmlMatcher
     */
    public static function htmlPiece($elementMatcher= null)
    {
        return new static($elementMatcher);
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
        $description->appendText('valid html piece ');
        if ($this->elementMatcher) {
            $description->appendDescriptionOf($this->elementMatcher);
        }
    }

    protected function matchesWithDiagnosticDescription($html, Description $mismatchDescription)
    {
        $internalErrors = libxml_use_internal_errors(true);
        $document = new \DOMDocument();

        if (!@$document->loadHTML($html)) {
            $mismatchDescription->appendText('there was some parsing error');
            return false;
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

            $mismatchDescription->appendText('there was parsing error: ')
                ->appendText(trim($error->message))
                ->appendText(' on line ')
                ->appendText($error->line);
            $result = false;
        }

        if ($result === false) {
            return $result;
        }
        $mismatchDescription->appendText('valid html piece ');

        if ($this->elementMatcher) {
            $result = $this->elementMatcher->matches($document);
            $this->elementMatcher->describeMismatch($document, $mismatchDescription);
        }

        $mismatchDescription->appendText("\nActual html:\n")->appendText($html);

        return $result;
    }

    private function isUnknownTagError(\LibXMLError $error)
    {
        return $error->code === self::XML_UNKNOWN_TAG_ERROR_CODE;
    }
}
