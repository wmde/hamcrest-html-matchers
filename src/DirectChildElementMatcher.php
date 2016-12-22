<?php

namespace Bekh6ex\HamcrestHtml;


use Hamcrest\Description;
use Hamcrest\TypeSafeDiagnosingMatcher;

class DirectChildElementMatcher extends TypeSafeDiagnosingMatcher
{
    /**
     * @var
     */
    private $matcher;

    public function __construct($matcher = null)
    {
        parent::__construct(\DOMNode::class);
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
        // TODO: Implement describeTo() method.
    }

    /**
     * Subclasses should implement these. The item will already have been checked for
     * the specific type.
     */
    protected function matchesSafelyWithDiagnosticDescription($item, Description $mismatchDescription)
    {
        $DOMNodeList = iterator_to_array($item->documentElement->childNodes);

        $body = array_shift($DOMNodeList);
        $DOMNodeList = iterator_to_array($body->childNodes);

        if (count($DOMNodeList) == 0) {
            return false;
        }
        $target = array_shift($DOMNodeList);
        if ($this->matcher) {
            return $target && $this->matcher->matches($target);
        }

        return true;
        // TODO: Implement matchesSafelyWithDiagnosticDescription() method.
    }
}