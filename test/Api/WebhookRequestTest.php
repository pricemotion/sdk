<?php

namespace Pricemotion\Sdk\Api;

use PHPUnit\Framework\TestCase;
use Pricemotion\Sdk\Crypto\SignatureVerifier;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Pricemotion\Sdk\Crypto\InvalidMessageException;

class WebhookRequestTest extends TestCase {
    public function testWebhookRequest(): void {
        $webhookRequestFactory = new WebhookRequestFactory($this->makeVerifier());
        $requestBody = file_get_contents(__DIR__ . '/../resources/webhook-payload');
        $webhookRequest = $webhookRequestFactory->createFromRequestBody($requestBody);
        $product = $webhookRequest->getProduct();
        $this->assertEquals('0088381179522', $product->getEan()->toString());
    }

    public function testInput(): void {
        $webhookRequestFactory = new WebhookRequestFactory($this->makeVerifier());
        $this->expectException(InvalidMessageException::class);
        $webhookRequestFactory->createFromInput();
    }

    private function makeVerifier(): SignatureVerifier {
        $cache = new ArrayAdapter();
        return new SignatureVerifier($cache);
    }
}
