<?php

namespace WMDE\HamcrestHtml;
use Hamcrest\Description;
use Hamcrest\Matcher;

/**
 * @license GPL-2.0+
 */
class ClassMatcher extends TagMatcher
{
    /**
     * @var Matcher
     */
    private $classMatcher;

    public function __construct(Matcher $class)
    {
        parent::__construct();
        $this->classMatcher = $class;
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
        $description->appendText('with class ')->appendDescriptionOf($this->classMatcher);
    }

    /**
     * Subclasses should implement these. The item will already have been checked for
     * the specific type.
     */
    protected function matchesSafelyWithDiagnosticDescription($item, Description $mismatchDescription)
    {
        /** @var \DOMElement $item */
        $classAttribute = $item->getAttribute('class');

        $classes = preg_split('/\s+/u', $classAttribute);
        foreach ($classes as $class) {
            if ($this->classMatcher->matches($class)) {
                return true;
            }
        }

        return false;
    }
}
