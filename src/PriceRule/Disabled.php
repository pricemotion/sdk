<?php
namespace Pricemotion\Sdk\PriceRule;

class Disabled implements PriceRuleInterface {
    public function calculate(Product $product): ?float {
        return null;
    }
}
