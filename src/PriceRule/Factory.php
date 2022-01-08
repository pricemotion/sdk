<?php
namespace Pricemotion\Sdk\PriceRule;

use Pricemotion\Sdk\PriceRule\Disabled;
use Pricemotion\Sdk\PriceRule\EqualToPosition;
use Pricemotion\Sdk\PriceRule\PriceRuleInterface;

class Factory {
    private const RULE_CLASSES = [
        'disabled' => Disabled::class,
        'percentageBelowAverage' => PercentageBelowAverage::class,
        'equalToPosition' => EqualToPosition::class,
        'lessThanPosition' => LessThanPosition::class,
    ];

    public function fromArray(array $data): PriceRuleInterface {
        if (empty($data['rule'])) {
            throw new \InvalidArgumentException('Price rule JSON misses rule element');
        }
        if (!isset(self::RULE_CLASSES[$data['rule']])) {
            throw new \InvalidArgumentException("Invalid price rule: {$data['rule']}");
        }
        $class = new \ReflectionClass(self::RULE_CLASSES[$data['rule']]);
        if (!$class->getConstructor() || $class->getConstructor()->getNumberOfRequiredParameters() == 0) {
            $rule = $class->newInstance();
        } elseif (!isset($data[$data['rule']])) {
            throw new \InvalidArgumentException("Required parameter missing for rule: {$data['rule']}");
        } else {
            $rule = $class->newInstance($data[$data['rule']]);
        }
        if (!$rule instanceof PriceRuleInterface) {
            throw new \LogicException("{$class->getName()} must implement PriceRuleInterface");
        }
        return $rule;
    }
}
