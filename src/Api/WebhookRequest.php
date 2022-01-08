<?php declare(strict_types=1);

namespace Pricemotion\Sdk\Api;

use Pricemotion\Sdk\Data\Product;
use Pricemotion\Sdk\RuntimeException;

class WebhookRequest {
    private Product $product;

    public function __construct(array $data) {
        if (empty($data['xml'])) {
            throw new RuntimeException("Webhook request body does not have a 'xml' element");
        }

        $dom = new \DOMDocument();
        if (!$dom->loadXML($data['xml'])) {
            throw new RuntimeException('Could not parse webhook XML payload');
        }

        $this->product = Product::fromXmlResponse($dom);
    }

    public function getProduct(): Product {
        return $this->product;
    }
}
