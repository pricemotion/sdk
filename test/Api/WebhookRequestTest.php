<?php

namespace Pricemotion\Sdk\Api;

use PHPUnit\Framework\TestCase;
use Pricemotion\Sdk\Crypto\SignatureVerifier;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class WebhookRequestTest extends TestCase {
    public function testWebhookRequest(): void {
        $cache = new ArrayAdapter();
        $verifier = new SignatureVerifier($cache);
        $webhookRequestFactory = new WebhookRequestFactory($verifier);
        $requestBody = file_get_contents(__DIR__ . '/../resources/webhook-payload');
        $webhookRequest = $webhookRequestFactory->createFromRequestBody($requestBody);
        $product = $webhookRequest->getProduct();
        $this->assertEquals('0088381179522', $product->getEan()->toString());
    }
}
