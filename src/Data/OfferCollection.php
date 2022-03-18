<?php
namespace Pricemotion\Sdk\Data;

use Pricemotion\Sdk\Util\Xml;

class OfferCollection implements \IteratorAggregate {
    /** @var Offer[] $offers */
    private $offers;

    private function __construct(array $offers) {
        $this->offers = array_values($offers);
        usort($this->offers, function (Offer $a, Offer $b) {
            return $a->getPrice() <=> $b->getPrice() ?: $a->getSeller() <=> $b->getSeller();
        });
    }

    public static function fromNode(\DOMNode $element): self {
        $offers = [];
        foreach (Xml::getAll($element, 'bezorg/item') as $item) {
            $offers[] = Offer::fromElement($item);
        }
        return new self($offers);
    }

    public function getIterator() {
        yield from $this->offers;
    }
}
