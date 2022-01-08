<?php
namespace Pricemotion\Sdk\PriceRule;

use Pricemotion\Sdk\Data\Product;

class EqualToPosition implements PriceRuleInterface {
    private $value;

    public function __construct($value) {
        $this->value = (int) $value;
        if ($this->value < 1) {
            throw new \InvalidArgumentException('Equal to position value must be at least 1');
        }
    }

    public function calculate(Product $product): ?float {
        $position = 0;
        foreach ($product->getOffers() as $offer) {
            if (++$position >= $this->value) {
                return $offer->getPrice();
            }
        }
        if (isset($offer)) {
            return $offer->getPrice() + 0.01;
        }
        return null;
    }
}
