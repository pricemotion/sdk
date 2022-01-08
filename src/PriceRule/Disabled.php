<?php
namespace Pricemotion\Sdk\PriceRule;

use Pricemotion\Sdk\Data\Product;

class Disabled implements PriceRuleInterface {
    public function calculate(Product $product): ?float {
        return null;
    }
}
