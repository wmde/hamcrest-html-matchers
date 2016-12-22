<?php

namespace Bekh6ex\HamcrestHtml;

use Hamcrest\Description;
use Hamcrest\Matcher;

class HtmlMatcher implements Matcher
{
    /**
     * @var RootElementMatcher
     */
    private $elementMatcher;

    /**
     * HtmlMatcher constructor.
     */
    public function __construct(RootElementMatcher $elementMatcher = null)
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
     * Evaluates the matcher for argument <var>$item</var>.
     *
     * @param mixed $html the object against which the matcher is evaluated.
     *
     * @return boolean <code>true</code> if <var>$item</var> matches,
     *   otherwise <code>false</code>.
     *
     * @see Hamcrest\BaseMatcher
     */
    public function matches($html)
    {
        $internalErrors = libxml_use_internal_errors(true);
        $DOMDocument = new \DOMDocument();

        if (!@$DOMDocument->loadHTML($html)) {
            return false;
        }


        $errors = libxml_get_errors();
        libxml_clear_errors();
        libxml_use_internal_errors($internalErrors);

        $result = true;
        foreach ($errors as $error) {
            $result = false;
        }

        if ($this->elementMatcher) {
            return $result && $this->elementMatcher->matches($DOMDocument);
        }

        return $result;

    }

    /**
     * Generate a description of why the matcher has not accepted the item.
     * The description will be part of a larger description of why a matching
     * failed, so it should be concise.
     * This method assumes that <code>matches($item)</code> is false, but
     * will not check this.
     *
     * @param mixed $item The item that the Matcher has rejected.
     * @param Description $description
     * @return
     */
    public function describeMismatch($item, Description $description)
    {
        // TODO: Implement describeMismatch() method.
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
}
