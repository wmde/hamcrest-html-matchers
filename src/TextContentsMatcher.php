<?php

namespace WMDE\HamcrestHtml;
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

    public function __construct(Matcher $matcher)
    {
        parent::__construct();
        $this->matcher = $matcher;
    }

    public function describeTo(Description $description)
    {
        $description->appendText('having text contents ')->appendDescriptionOf($this->matcher);
    }

    /**
     * @param \DOMElement $item
     * @param Description $mismatchDescription
     *
     * @return bool
     */
    protected function matchesSafelyWithDiagnosticDescription($item, Description $mismatchDescription)
    {
        return $this->matcher->matches($item->textContent);
    }
}
