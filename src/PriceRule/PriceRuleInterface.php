<?php
namespace Pricemotion\Sdk\PriceRule;

use Pricemotion\Sdk\Data\Product;

interface PriceRuleInterface {
    public function calculate(Product $product): ?float;
}
