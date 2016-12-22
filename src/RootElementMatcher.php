<?php

namespace Bekh6ex\HamcrestHtml;

use Hamcrest\Description;
use Hamcrest\TypeSafeDiagnosingMatcher;

class RootElementMatcher extends TypeSafeDiagnosingMatcher
{
    /**
     * @var TagMatcher
     */
    private $tagMatcher;

    /**
     * TagMatcher constructor.
     */
    public function __construct(TagMatcher $tagMatcher = null)
    {
        parent::__construct(self::TYPE_OBJECT, \DOMDocument::class);
        $this->tagMatcher = $tagMatcher;
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
     * @param \DOMDocument $item
     * @param Description $mismatchDescription
     */
    protected function matchesSafelyWithDiagnosticDescription($item, Description $mismatchDescription)
    {
        $DOMNodeList = iterator_to_array($item->documentElement->childNodes);

        $body = array_shift($DOMNodeList);
        $DOMNodeList = iterator_to_array($body->childNodes);
        if (count($DOMNodeList) > 1) {
            return false;
        }
        $target = array_shift($DOMNodeList);
        if ($this->tagMatcher) {
            return $target && $this->tagMatcher->matches($target);
        }

        return (bool)$target;
        // TODO: Implement matchesSafelyWithDiagnosticDescription() method.

    }
}
