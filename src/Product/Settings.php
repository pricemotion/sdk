<?php declare(strict_types=1);

namespace Pricemotion\Sdk\Product;

use Pricemotion\Sdk\PriceRule\Factory;
use Pricemotion\Sdk\PriceRule\PriceRuleInterface;

class Settings {
    private PriceRuleInterface $priceRule;

    private ?float $minimumMarginPercentage = null;

    private ?float $maximumListPriceDiscountPercentage = null;

    private float $roundPrecision = 0.01;

    private bool $roundUp = false;

    private function __construct() {
    }

    public static function fromArray(array $data): self {
        $settings = new self();
        $settings->priceRule = (new Factory())->fromArray($data);
        if (!empty($data['protectMargin'])) {
            $settings->minimumMarginPercentage = (float) ($data['minimumMargin'] ?? 0);
        }
        if (!empty($data['limitListPriceDiscount'])) {
            $settings->maximumListPriceDiscountPercentage = (float) ($data['maximumListPriceDiscount'] ?? 0);
        }
        if (!empty($data['roundPrecision']) && ($roundPrecision = (float) $data['roundPrecision']) > 0.01) {
            $settings->roundPrecision = $roundPrecision;
            $settings->roundUp = !empty($data['roundUp']);
        }
        return $settings;
    }

    public function getPriceRule(): PriceRuleInterface {
        return $this->priceRule;
    }

    public function getMinimumMarginPercentage(): ?float {
        return $this->minimumMarginPercentage;
    }

    public function getMaximumListPriceDiscountPercentage(): ?float {
        return $this->maximumListPriceDiscountPercentage;
    }

    public function getRoundPrecision(): float {
        return $this->roundPrecision;
    }

    public function getRoundUp(): bool {
        return $this->roundUp;
    }
}
