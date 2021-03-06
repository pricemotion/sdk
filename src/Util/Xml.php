<?php
namespace Pricemotion\Sdk\Util;

class Xml {
    public static function getFloat(\DOMNode $root, string $query): float {
        return (float) self::getText($root, $query);
    }

    public static function getText(\DOMNode $root, string $query): string {
        return trim(self::get($root, $query)->textContent);
    }

    public static function get(\DOMNode $root, string $query): \DOMNode {
        $elements = self::getAll($root, $query);
        if ($elements->length === 0) {
            throw new Xml\NoElementsException("Got no element from query '{$query}'");
        }
        if ($elements->length !== 1) {
            throw new Xml\TooManyElementsException("Got too many results ({$elements->length}) from query '{$query}'");
        }
        /** @phan-suppress-next-line PhanTypeMismatchReturnNullable */
        return $elements->item(0);
    }

    public static function getAll(\DOMNode $root, string $query): \DOMNodeList {
        return (new \DOMXPath($root->ownerDocument))->query($query, $root);
    }
}
