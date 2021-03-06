<?php
namespace Pricemotion\Sdk\PriceRule;

use PHPUnit\Framework\TestCase;
use Pricemotion\Sdk\Data\ProductTest;

class EqualToPositionTest extends TestCase {
    public function testCalculate() {
        $product = ProductTest::getProduct();
        $this->assertEquals(52.5, (new EqualToPosition(1))->calculate($product));
        $this->assertEquals(60.06, (new EqualToPosition(3))->calculate($product));
        $this->assertEquals(69.51, (new EqualToPosition(10))->calculate($product));
    }
}
