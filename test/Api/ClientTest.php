<?php declare(strict_types=1);

namespace Pricemotion\Sdk\Api;

use PHPUnit\Framework\TestCase;
use Pricemotion\Sdk\Data\EanCollection;
use Pricemotion\Sdk\Data\Ean;

class ClientTest extends TestCase {
    public function testFollowProducts(): void {
        $eans = EanCollection::fromStrings(['0077985029579', '5025155025710']);
        $result = $this->getClient()->followProducts($eans);
        $this->assertCount(2, $result->getData());
    }

    public function testGetProduct(): void {
        $result = $this->getClient()->getProduct(Ean::fromString('0047871061020'));
        $this->assertGreaterThan(0, $result->getLowestPrice());
    }

    private function getClient(): Client {
        $apiKey = getenv('PRICEMOTION_API_KEY');
        if (!$apiKey) {
            $this->markTestSkipped('PRICEMOTION_API_KEY is not set');
        }
        return new Client($apiKey);
    }
}
