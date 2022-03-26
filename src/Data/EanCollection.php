<?php declare(strict_types=1);

namespace Pricemotion\Sdk\Data;

/** @inherits Collection<Ean> */
class EanCollection extends Collection {
    protected function isValidElement($element): bool {
        return $element instanceof Ean;
    }

    public static function fromStrings(array $strings): self {
        return new self(
            array_map(function (string $string): Ean {
                return Ean::fromString($string);
            }, $strings),
        );
    }
}
