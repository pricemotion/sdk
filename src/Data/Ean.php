<?php
namespace Pricemotion\Sdk\Data;

use JsonSerializable;
use Pricemotion\Sdk\InvalidArgumentException;

class Ean implements JsonSerializable {
    private $value;

    private function __construct(string $ean) {
        $ean = trim($ean);
        $ean = ltrim($ean, '0');
        if (!preg_match('/^[0-9]{8,14}$/', $ean)) {
            throw new InvalidArgumentException('EAN must consist of 8 to 14 digits');
        }
        if (!$this->check($ean)) {
            throw new InvalidArgumentException('EAN check digit is invalid');
        }
        $this->value = $ean;
    }

    private function check(string $ean): bool {
        $chars = str_split($ean);
        $check_digit = (int) array_pop($chars);
        $result = 0;
        foreach (array_reverse($chars) as $i => $c) {
            $multiplier = $i % 2 ? 1 : 3;
            $result += (int) $c * $multiplier;
        }
        $result = (10 - ($result % 10)) % 10;
        return $result === $check_digit;
    }

    public static function fromString(string $ean): self {
        return new self($ean);
    }

    public function __toString() {
        return $this->toString();
    }

    public function toString(): string {
        return $this->value;
    }

    public function jsonSerialize(): string {
        return $this->toString();
    }
}
