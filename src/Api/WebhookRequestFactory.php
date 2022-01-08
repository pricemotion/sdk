<?php declare(strict_types=1);

namespace Pricemotion\Sdk\Api;

use Pricemotion\Sdk\Crypto\SignatureVerifier;

class WebhookRequestFactory {
    private SignatureVerifier $signatureVerifier;

    public function __construct(SignatureVerifier $signatureVerifier) {
        $this->signatureVerifier = $signatureVerifier;
    }

    public function createFromRequestBody(string $body): WebhookRequest {
        $json = $this->signatureVerifier->open($body);
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        return new WebhookRequest($data);
    }
}
