<?php

namespace Bekh6ex\HamcrestHtml;

use Hamcrest\TypeSafeDiagnosingMatcher;

abstract class TagMatcher extends TypeSafeDiagnosingMatcher
{
    /**
     * TagMatcher constructor.
     */
    public function __construct()
    {
        parent::__construct(self::TYPE_OBJECT, \DOMElement::class);
    }
}
