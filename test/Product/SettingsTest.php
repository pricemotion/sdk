<?php declare(strict_types=1);

namespace Pricemotion\Sdk\Product;

use PHPUnit\Framework\TestCase;
use Pricemotion\Sdk\Data\ProductTest;
use Pricemotion\Sdk\PriceRule\Disabled;
use Pricemotion\Sdk\PriceRule\EqualToPosition;
use Psr\Log\AbstractLogger;

class SettingsTest extends TestCase {
    public function testSettings(): void {
        $settings = Settings::fromArray([
            'rule' => 'equalToPosition',
            'percentageBelowAverage' => null,
            'equalToPosition' => '3',
            'lessThanPosition' => null,
            'protectMargin' => true,
            'minimumMargin' => '10',
            'limitListPriceDiscount' => true,
            'maximumListPriceDiscount' => '50',
            'roundPrecision' => 0.1,
            'roundUp' => true,
        ]);

        $this->assertInstanceOf(EqualToPosition::class, $settings->getPriceRule());
        $this->assertSame(10., $settings->getMinimumMarginPercentage());
        $this->assertSame(50., $settings->getMaximumListPriceDiscountPercentage());
        $this->assertTrue($settings->getRoundUp());
        $this->assertSame(0.1, $settings->getRoundPrecision());

        $product = new class implements ProductInterface {
            public function getId(): string {
                return '';
            }

            public function getPrice(): float {
                return 100.0;
            }

            public function getCostPrice(): ?float {
                return 10.0;
            }

            public function getListPrice(): ?float {
                return null;
            }
        };
        $pricemotionProduct = ProductTest::getProduct();
        $this->assertSame(60.1, $settings->getNewPrice($product, $pricemotionProduct));
    }

    public function testEmpty(): void {
        $settings = Settings::fromArray(['rule' => 'disabled']);
        $this->assertInstanceOf(Disabled::class, $settings->getPriceRule());
        $this->assertNull($settings->getMinimumMarginPercentage());
        $this->assertNull($settings->getMaximumListPriceDiscountPercentage());
        $this->assertFalse($settings->getRoundUp());
        $this->assertSame(0.01, $settings->getRoundPrecision());
    }
}
