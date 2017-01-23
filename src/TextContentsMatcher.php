<?php

namespace Bekh6ex\HamcrestHtml;
use Hamcrest\Description;
use Hamcrest\Matcher;

/**
 * @license GPL-2.0+
 */
class TextContentsMatcher extends TagMatcher
{
    /**
     * @var Matcher
     */
    private $matcher;

    /**
     * TextContentsMatcher constructor.
     */
    public function __construct(Matcher $matcher)
    {
        parent::__construct();
        $this->matcher = $matcher;
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
        $description->appendText('having text contents ')->appendDescriptionOf($this->matcher);
    }

    /**
     * Subclasses should implement these. The item will already have been checked for
     * the specific type.
     */
    protected function matchesSafelyWithDiagnosticDescription($item, Description $mismatchDescription)
    {
        /** @var \DOMElement $item */
        return $this->matcher->matches($item->textContent);
    }
}