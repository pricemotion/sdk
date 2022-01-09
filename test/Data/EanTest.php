<?php declare(strict_types=1);

namespace Pricemotion\Sdk\Data;

use PHPUnit\Framework\TestCase;
use Pricemotion\Sdk\InvalidArgumentException;

class EanTest extends TestCase {
    public function testDigits(): void {
        $this->expectException(InvalidArgumentException::class);
        Ean::fromString('a88381179522');
    }

    public function testPadding(): void {
        $ean = Ean::fromString('88381179522');
        $this->assertSame('0088381179522', $ean->toString());
    }
}
