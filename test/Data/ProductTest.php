<?php
namespace Pricemotion\Sdk\Data;

use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase {
    public function testFromXmlResponse() {
        $product = self::getProduct();

        $this->assertSame('Surel Gietijzeren wok mat zwart', $product->getName());
        $this->assertSame('8680131811038', $product->getEan()->toString());
        $this->assertEquals(52.5, $product->getLowestPrice());
        $this->assertEquals(60.67, $product->getAveragePrice());
        $this->assertEquals(61.0, $product->getMedianPrice());
        $this->assertEquals(69.5, $product->getHighestPrice());

        $this->assertCount(5, $product->getOffers());
        $lastOffer = null;
        /** @var Offer $offer */
        foreach ($product->getOffers() as $offer) {
            if ($lastOffer) {
                $this->assertGreaterThanOrEqual($lastOffer->getPrice(), $offer->getPrice());
            }
            $lastOffer = $offer;
        }

        $this->assertSame('Blokker.nl', $lastOffer->getSeller());
        $this->assertEquals(69.5, $lastOffer->getPrice());

        $this->assertSame($product->getOffers(), $product->getOffers());
        $this->assertSame($product->getEan(), $product->getEan());
    }

    public static function getProduct(): Product {
        $document = new \DOMDocument();
        $document->load(__DIR__ . '/../resources/product.xml');
        return Product::fromXmlResponse($document);
    }
}
