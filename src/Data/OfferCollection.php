<?php
namespace Pricemotion\Sdk\Data;

class OfferCollection implements \IteratorAggregate {
    /** @var Offer[] $offers */
    private $offers;

    private function __construct(array $offers) {
        $this->offers = $offers;
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
        foreach ($this->offers as $offer) {
            yield $offer;
        }
    }
}
