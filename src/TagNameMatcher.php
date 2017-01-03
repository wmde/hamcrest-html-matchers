<?php

namespace Bekh6ex\HamcrestHtml;

use Hamcrest\Description;
use Hamcrest\Matcher;

class TagNameMatcher extends TagMatcher
{
    /**
     * @var
     */
    private $tagNameMatcher;

    /**
     * TagNameMatcher constructor.
     */
    public function __construct(Matcher $tagNameMcthcer)
    {
        parent::__construct();
        $this->tagNameMatcher = $tagNameMcthcer;
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
        $description->appendText('with tag name ')
            ->appendDescriptionOf($this->tagNameMatcher);
    }

    /**
     * Subclasses should implement these. The item will already have been checked for
     * the specific type.
     * @param \DOMElement $item
     * @param Description $mismatchDescription
     */
    protected function matchesSafelyWithDiagnosticDescription($item, Description $mismatchDescription)
    {
        $mismatchDescription->appendText("tag name ");
        $this->tagNameMatcher->describeMismatch($item->tagName, $mismatchDescription);
        return $this->tagNameMatcher->matches($item->tagName);
    }
}
