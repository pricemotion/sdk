<?php declare(strict_types=1);

namespace Pricemotion\Sdk\Api;

use Pricemotion\Sdk\Data\Ean;
use Pricemotion\Sdk\Data\EanCollection;
use Pricemotion\Sdk\RuntimeException;

class Client {
    private $ch;

    private $apiKey;

    public function __construct(string $apiKey) {
        $this->apiKey = $apiKey;
    }

    public function followProducts(EanCollection $eans): FollowProductsResponse {
        $ch = $this->getCurlHandle();
        if (
            !curl_setopt_array($ch, [
                CURLOPT_URL => 'https://www.pricemotion.nl/api/product/follow',
                CURLOPT_USERPWD => "{$this->apiKey}:",
                CURLOPT_POSTFIELDS => json_encode(
                    array_map(function (Ean $ean): string {
                        return $ean->toString();
                    }, $eans->toArray()),
                ),
                CURLOPT_FAILONERROR => true,
                CURLOPT_RETURNTRANSFER => true,
            ])
        ) {
            throw new RuntimeException('curl_setopt_array failed');
        }
        $response = curl_exec($ch);
        if ($response === false) {
            throw new RuntimeException(sprintf('curl_exec failed: (%d) %s', curl_errno($ch), curl_error($ch)));
        }
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
        return new FollowProductsResponse($data);
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
