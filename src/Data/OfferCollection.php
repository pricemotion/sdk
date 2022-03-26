<?php
namespace Pricemotion\Sdk\Data;

use Pricemotion\Sdk\Util\Xml;

/** @inherits Collection<Offer> */
class OfferCollection extends Collection {
    protected function isValidElement($element): bool {
        return $element instanceof Offer;
    }

    /**
     * @param Offer[] $elements
     * @return Offer[]
     */
    protected function sortElements(array $elements): array {
        usort($elements, function (Offer $a, Offer $b) {
            return $a->getPrice() <=> $b->getPrice() ?: $a->getSeller() <=> $b->getSeller();
        });
        return $elements;
    }

    public static function fromNode(\DOMNode $element): self {
        $offers = [];
        foreach (Xml::getAll($element, 'bezorg/item') as $item) {
            $offers[] = Offer::fromElement($item);
        }
        return new self($offers);
    }
}
