<?php
namespace Pricemotion\Sdk\Data;

use Pricemotion\Sdk\RuntimeException;
use Pricemotion\Sdk\Util\Xml;

class Product {
    private Ean $ean;

    private float $lowestPrice;

    private float $averagePrice;

    private OfferCollection $offers;

    private function __construct() {
    }

    public static function fromXmlResponse(\DOMDocument $document): self {
        $root = $document->documentElement;

        if (!strcasecmp($root->tagName, 'error')) {
            throw new RuntimeException('API error: ' . trim($root->textContent));
        }

        if (strcasecmp($root->tagName, 'response')) {
            throw new RuntimeException("Response root element should be <response>, not <{$root->tagName}>");
        }

        $product = new self();
        $product->ean = Ean::fromString(Xml::getText($root, 'info/ean'));
        $product->lowestPrice = Xml::getFloat($root, 'info/price/min');
        $product->averagePrice = Xml::getFloat($root, 'info/price/avg');
        $product->offers = OfferCollection::fromNode(Xml::get($root, 'prices'));

        return $product;
    }

    public function getLowestPrice(): float {
        return $this->lowestPrice;
    }

    public function getAveragePrice(): float {
        return $this->averagePrice;
    }

    public function getOffers(): OfferCollection {
        return $this->offers;
    }

    public function getEan(): Ean {
        return $this->ean;
    }
}
