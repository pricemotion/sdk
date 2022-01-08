<?php
namespace Pricemotion\Sdk\PriceRule;

use PHPUnit\Framework\TestCase;
use Pricemotion\Sdk\Data\ProductTest;
use Pricemotion\Sdk\PriceRule\PercentageBelowAverage;

class PercentageBelowAverageTest extends TestCase {
    public function testCalculate() {
        $product = ProductTest::getProduct();
        $this->assertEquals(45.50, (new PercentageBelowAverage(25))->calculate($product));
    }
}
