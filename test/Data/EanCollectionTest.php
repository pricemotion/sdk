<?php declare(strict_types=1);

namespace Pricemotion\Sdk\Data;

use PHPUnit\Framework\TestCase;

class EanCollectionTest extends TestCase {
    public function testCount() {
        $collection = EanCollection::fromStrings(['4242002514215']);

        $this->assertEquals(1, $collection->count());
    }
}
