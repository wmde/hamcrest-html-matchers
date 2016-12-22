<?php

class TagNameMatcher extends \Bekh6ex\HamcrestHtml\TagMatcher
{
    /**
     * @var \Hamcrest\Matcher
     */
    private $tagNameMatcher;

    public function __construct(\Hamcrest\Matcher $tagNameMatcher)
    {
        parent::__construct();
        $this->tagNameMatcher = $tagNameMatcher;
    }


    /**
     * Generates a description of the object.  The description may be part
     * of a description of a larger object of which this is just a component,
     * so it should be worded appropriately.
     *
     * @param \Hamcrest\Description $description
     *   The description to be built or appended to.
     */
    public function describeTo(\Hamcrest\Description $description)
    {
        // TODO: Implement describeTo() method.
    }

    /**
     * Subclasses should implement these. The item will already have been checked for
     * the specific type.
     * @param \DOMElement $item
     * @param \Hamcrest\Description $mismatchDescription
     */
    protected function matchesSafelyWithDiagnosticDescription($item, \Hamcrest\Description $mismatchDescription)
    {
        return $this->tagNameMatcher->matches($item->tagName);
    }
}
