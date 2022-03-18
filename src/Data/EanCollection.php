<?php declare(strict_types=1);

namespace Pricemotion\Sdk\Data;

use Pricemotion\Sdk\InvalidArgumentException;

class EanCollection implements \IteratorAggregate {
    private $eans;

    public function __construct(array $eans) {
        foreach ($eans as $ean) {
            if (!$ean instanceof Ean) {
                throw new InvalidArgumentException('EanCollection can only contain Ean instances');
            }
        }
        $this->eans = array_values($eans);
    }

    public static function fromStrings(array $strings): self {
        return new self(
            array_map(function (string $string): Ean {
                return Ean::fromString($string);
            }, $strings),
        );
    }

    public function getIterator(): \Generator {
        yield from $this->eans;
    }

    public function toArray(): array {
        return $this->eans;
    }
}
