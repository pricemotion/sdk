<?php declare(strict_types=1);

namespace Pricemotion\Sdk\Api;

use PHPUnit\Framework\TestCase;
use Pricemotion\Sdk\Data\EanCollection;

class ClientTest extends TestCase {
    public function testFollowProducts(): void {
        $apiKey = getenv('PRICEMOTION_API_KEY');
        if (!$apiKey) {
            $this->markTestSkipped('PRICEMOTION_API_KEY is not set');
        }

        $client = new Client($apiKey);

        $eans = EanCollection::fromStrings(['0077985029579', '5025155025710']);

        $result = $client->followProducts($eans);

        $this->assertCount(2, $result->getData());
    }
}
