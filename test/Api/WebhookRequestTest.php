<?php

namespace Pricemotion\Sdk\Api;

use Cache\Adapter\PHPArray\ArrayCachePool;
use PHPUnit\Framework\TestCase;
use Pricemotion\Sdk\Crypto\SignatureVerifier;

class WebhookRequestTest extends TestCase {
    public function testWebhookRequest(): void {
        $cache = new ArrayCachePool();
        $verifier = new SignatureVerifier($cache);
        $webhookRequestFactory = new WebhookRequestFactory($verifier);
        $requestBody = file_get_contents(__DIR__ . '/../resources/webhook-payload');
        $webhookRequest = $webhookRequestFactory->createFromRequestBody($requestBody);
        $product = $webhookRequest->getProduct();
        $this->assertEquals('88381179522', $product->getEan()->toString());
    }
}
