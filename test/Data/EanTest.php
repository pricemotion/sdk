<?php declare(strict_types=1);

namespace Pricemotion\Sdk\Data;

use PHPUnit\Framework\TestCase;
use Pricemotion\Sdk\InvalidArgumentException;

class EanTest extends TestCase {
    public function testDigits() {
        $this->expectException(InvalidArgumentException::class);
        Ean::fromString('a88381179522');
    }
}
