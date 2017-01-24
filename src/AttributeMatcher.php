<?php

namespace WMDE\HamcrestHtml;

use Hamcrest\Description;
use Hamcrest\Matcher;
use Hamcrest\Util;

/**
 * @license GPL-2.0+
 */
class AttributeMatcher extends TagMatcher
{
    /**
     * @var Matcher
     */
    private $attributeNameMatcher;

    /**
     * @var Matcher|null
     */
    private $valueMatcher;

    /**
     * AttributeMatcher constructor.
     * @param \Hamcrest\Matcher $attributeNameMatcher
     */
    public function __construct(Matcher $attributeNameMatcher)
    {
        parent::__construct();

        $this->attributeNameMatcher = $attributeNameMatcher;
    }

    /**
     * @param Matcher|mixed $value
     * @return AttributeMatcher
     */
    public function havingValue($value)
    {
        //TODO: Throw exception if value is set
        $result = clone $this;
        $result->valueMatcher = Util::wrapValueWithIsEqual($value);

        return $result;
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
        $description->appendText('with attribute ')
            ->appendDescriptionOf($this->attributeNameMatcher);
        if ($this->valueMatcher) {
            $description->appendText(' having value ')
                ->appendDescriptionOf($this->valueMatcher);
        }
    }

    /**
     * Subclasses should implement these. The item will already have been checked for
     * the specific type.
     */
    protected function matchesSafelyWithDiagnosticDescription($item, Description $mismatchDescription)
    {
        /** @var \DOMElement $item */
        /** @var \DOMAttr $attribute */
        foreach ($item->attributes as $attribute) {
            if ($this->valueMatcher) {
                if (
                    $this->attributeNameMatcher->matches($attribute->name)
                    && $this->valueMatcher->matches($attribute->value)
                ) {
                    return true;
                }
            } else {
                if ($this->attributeNameMatcher->matches($attribute->name)) {
                    return true;
                }
            }
        }

        return false;
    }
}
