<?php

namespace WMDE\HamcrestHtml;


class XmlNodeRecursiveIterator extends \ArrayIterator
{

    /**
     * XmlNodeRecursiveIterator constructor.
     */
    public function __construct(\DOMNodeList $nodeList)
    {
        $queue = $this->addElementsToQueue([], $nodeList);
        parent::__construct($queue);
    }

    private function addElementsToQueue(array $queue, \DOMNodeList $nodeList)
    {
        /** @var \DOMElement $node */
        foreach ($nodeList as $node) {
            $queue[] = $node;
            if ($node->childNodes !== null) {
                $queue = $this->addElementsToQueue($queue, $node->childNodes);
            }
        }

        return $queue;
    }
}
