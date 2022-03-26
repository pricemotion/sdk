<?php declare(strict_types=1);

namespace Pricemotion\Sdk\Data;

use Pricemotion\Sdk\InvalidArgumentException;
use ReflectionClass;

/** @template T */
abstract class Collection implements \IteratorAggregate {
    /** @var T[] */
    private $elements;

    /** @param T[] $elements */
    public function __construct(array $elements = []) {
        $elements = array_values($elements);
        foreach ($elements as $element) {
            if (!$this->isValidElement($element)) {
                throw new InvalidArgumentException(
                    sprintf('Invalid element passed to %s', (new ReflectionClass($this))->getShortName()),
                );
            }
        }
        $elements = $this->sortElements($elements);
        $this->elements = $elements;
    }

    /**
     * @param T[] $elements
     * @return T[]
     */
    protected function sortElements(array $elements): array {
        return $elements;
    }

    abstract protected function isValidElement($element): bool;

    /** @return \Traversable<T> */
    public function getIterator(): \Traversable {
        yield from $this->elements;
    }

    /** @return T[] */
    public function toArray(): array {
        return $this->elements;
    }

    public function count(): int {
        return sizeof($this->elements);
    }
}
