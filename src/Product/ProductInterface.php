<?php declare(strict_types=1);

namespace Pricemotion\Sdk\Product;

interface ProductInterface {
    public function getId(): string;
    public function getPrice(): float;
    public function getCostPrice(): ?float;
    public function getListPrice(): ?float;
}
