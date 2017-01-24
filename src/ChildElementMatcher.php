<?php

namespace WMDE\HamcrestHtml;

use Hamcrest\Description;
use Hamcrest\Matcher;
use Hamcrest\TypeSafeDiagnosingMatcher;

class ChildElementMatcher extends TypeSafeDiagnosingMatcher
{
    /**
     * @var Matcher|null
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
        $description->appendText('having child ');
        if ($this->matcher) {
            $description->appendDescriptionOf($this->matcher);
        }
    }

    /**
     * Subclasses should implement these. The item will already have been checked for
     * the specific type.
     */
    protected function matchesSafelyWithDiagnosticDescription($item, Description $mismatchDescription)
    {
        if ($item instanceof \DOMDocument) {
            $directChildren = iterator_to_array($item->documentElement->childNodes);

            $body = array_shift($directChildren);
            $directChildren = $body->childNodes;
        } else {
            $directChildren = $item->childNodes;
        }

        if ($directChildren->length === 0) {
            $mismatchDescription->appendText('having no children');
            return false;
        }

        if (!$this->matcher) {
            return $directChildren->length > 0;
        }

        foreach (new XmlNodeRecursiveIterator($directChildren) as $child) {
            if ($this->matcher && $this->matcher->matches($child)) {
                return true;
            }
        }

        $mismatchDescription->appendText('having no children ')->appendDescriptionOf($this->matcher);
        return false;
    }
}
