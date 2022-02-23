<?php
namespace Pricemotion\Sdk\Data;

use Pricemotion\Sdk\RuntimeException;
use Pricemotion\Sdk\Util\Xml;

class Product {
    private \DOMElement $root;

    private Ean $ean;

    private OfferCollection $offers;

    private function __construct(\DOMElement $root) {
        $this->root = $root;
    }

    public static function fromXmlResponse(\DOMDocument $document): self {
        $root = $document->documentElement;

        if (!strcasecmp($root->tagName, 'error')) {
            throw new RuntimeException('API error: ' . trim($root->textContent));
        }

        if (strcasecmp($root->tagName, 'response')) {
            throw new RuntimeException("Response root element should be <response>, not <{$root->tagName}>");
        }

        return new self($root);
    }

    public function getName(): string {
        return Xml::getText($this->root, 'info/name');
    }

    public function getEan(): Ean {
        if (!isset($this->ean)) {
            $this->ean = Ean::fromString(Xml::getText($this->root, 'info/ean'));
        }
        return $this->ean;
    }

    public function getLowestPrice(): float {
        return Xml::getFloat($this->root, 'info/price/min');
    }

    public function getAveragePrice(): float {
        return Xml::getFloat($this->root, 'info/price/avg');
    }

    public function getMedianPrice(): float {
        return Xml::getFloat($this->root, 'info/price/median');
    }

    public function getHighestPrice(): float {
        return Xml::getFloat($this->root, 'info/price/max');
    }

    public function getOffers(): OfferCollection {
        if (!isset($this->offers)) {
            $this->offers = OfferCollection::fromNode(Xml::get($this->root, 'prices'));
        }
        return $this->offers;
    }
}
