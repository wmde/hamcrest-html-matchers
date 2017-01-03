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
        $description->appendText('having root element ');
        if ($this->tagMatcher) {
            $description->appendDescriptionOf($this->tagMatcher);
        }
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
            //TODO Test this description
            $mismatchDescription->appendText('having ' . count($DOMNodeList) . ' root elements ');
            return false;
        }

        $target = array_shift($DOMNodeList);
        if (!$target) {
            //TODO Reproduce?
            $mismatchDescription->appendText('having no root elements ');
            return false;
        }
        if ($this->tagMatcher) {
            $mismatchDescription->appendText('root element ');
            $this->tagMatcher->describeMismatch($target, $mismatchDescription);
            return $this->tagMatcher->matches($target);
        }

        return (bool)$target;
        // TODO: Implement matchesSafelyWithDiagnosticDescription() method.

    }
}
