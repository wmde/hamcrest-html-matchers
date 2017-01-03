<?php

namespace Bekh6ex\HamcrestHtml;

use Hamcrest\Description;
use Hamcrest\DiagnosingMatcher;
use Hamcrest\Matcher;

class HtmlMatcher extends DiagnosingMatcher
{
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

//    public function matches($html)
//    {
//        $internalErrors = libxml_use_internal_errors(true);
//        $DOMDocument = new \DOMDocument();
//
//        if (!@$DOMDocument->loadHTML($html)) {
//            return false;
//        }
//
//
//        $errors = libxml_get_errors();
//        libxml_clear_errors();
//        libxml_use_internal_errors($internalErrors);
//
//        $result = true;
//        foreach ($errors as $error) {
//            $result = false;
//        }
//
//        if ($this->elementMatcher) {
//            return $result && $this->elementMatcher->matches($DOMDocument);
//        }
//
//        return $result;
//
//    }


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
            return $result;
        }

        return $result;
    }
}
