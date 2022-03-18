<?php declare(strict_types=1);

namespace Pricemotion\Sdk\Api;

class FollowProductsResponse {
    private $response;

    public function __construct(array $response) {
        $this->response = $response;
    }

    public function getData(): array {
        return $this->response;
    }
}
