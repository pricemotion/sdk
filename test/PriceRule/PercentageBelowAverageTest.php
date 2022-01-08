<?php
namespace Pricemotion\Sdk\PriceRule;

use PHPUnit\Framework\TestCase;
use Pricemotion\Sdk\Data\ProductTest;

class PercentageBelowAverageTest extends TestCase {
    public function testCalculate() {
        $product = ProductTest::getProduct();
        $this->assertEquals(45.5, (new PercentageBelowAverage(25))->calculate($product));
    }
}
