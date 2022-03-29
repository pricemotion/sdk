<?php declare(strict_types=1);

namespace Pricemotion\Sdk\Api;

use Pricemotion\Sdk\Data\Ean;
use Pricemotion\Sdk\Data\EanCollection;
use Pricemotion\Sdk\Data\Product;
use Pricemotion\Sdk\RuntimeException;

class Client {
    private $ch;

    private $apiKey;

    public function __construct(string $apiKey) {
        $this->apiKey = $apiKey;
    }

    public function followProducts(EanCollection $eans): FollowProductsResponse {
        $response = $this->curlRequest([
            CURLOPT_URL => 'https://www.pricemotion.nl/api/product/follow',
            CURLOPT_USERPWD => "{$this->apiKey}:",
            CURLOPT_POSTFIELDS => json_encode(
                array_map(fn(Ean $ean) => $ean->toString(), $eans->toArray()),
                JSON_THROW_ON_ERROR,
            ),
        ]);
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
        return new FollowProductsResponse($data);
    }

    public function getProduct(Ean $ean): Product {
        $response = $this->curlRequest([
            CURLOPT_URL =>
                'https://www.pricemotion.nl/service/?' .
                http_build_query([
                    'serial' => $this->apiKey,
                    'ean' => $ean->toString(),
                ]),
        ]);

        $dom = new \DOMDocument();
        if (!$dom->loadXML($response)) {
            throw new RuntimeException('Could not load Pricemotion product XML');
        }

        return Product::fromXmlResponse($dom);
    }

    private function curlRequest(array $options): string {
        $options += [
            CURLOPT_FAILONERROR => true,
            CURLOPT_RETURNTRANSFER => true,
        ];
        $ch = $this->getCurlHandle();
        if (!curl_setopt_array($ch, $options)) {
            throw new RuntimeException('curl_setopt_array failed');
        }
        $response = curl_exec($ch);
        if ($response === false) {
            throw new RuntimeException(sprintf('curl_exec failed: (%d) %s', curl_errno($ch), curl_error($ch)));
        }
        return $response;
    }

    private function getCurlHandle() {
        if (!isset($this->ch)) {
            $this->ch = curl_init();
            if (!$this->ch) {
                throw new RuntimeException('curl_init failed');
            }
        }
        curl_reset($this->ch);
        return $this->ch;
    }

    public function __destruct() {
        if (isset($this->ch)) {
            curl_close($this->ch);
        }
    }
}
