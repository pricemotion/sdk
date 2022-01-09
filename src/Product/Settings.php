<?php declare(strict_types=1);

namespace Pricemotion\Sdk\Product;

use Pricemotion\Sdk\Data\Product;
use Pricemotion\Sdk\PriceRule\Factory;
use Pricemotion\Sdk\PriceRule\PriceRuleInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Settings {
    private PriceRuleInterface $priceRule;

    private ?float $minimumMarginPercentage = null;

    private ?float $maximumListPriceDiscountPercentage = null;

    private float $roundPrecision = 0.01;

    private bool $roundUp = false;

    private LoggerInterface $logger;

    private function __construct() {
        $this->logger = new NullLogger();
    }

    public function setLogger(LoggerInterface $logger): void {
        $this->logger = $logger;
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

    public function getNewPrice(ProductInterface $product, Product $pricemotionProduct): ?float {
        $newPrice = $this->getPriceRule()->calculate($pricemotionProduct);
        if (!$newPrice) {
            return null;
        }

        $minimumMarginPercentage = $this->getMinimumMarginPercentage();
        if ($minimumMarginPercentage !== null) {
            $cost = (float) $product->getCostPrice();
            if ($cost < 0.01) {
                $this->logger->error(
                    sprintf('Margin protection enabled, but no cost price found for product %s', $product->getId()),
                );
                return null;
            }
            $minimumPrice = round($cost * (1 + $minimumMarginPercentage / 100), 2);
            if ($newPrice < $minimumPrice) {
                $this->logger->info(
                    sprintf(
                        'Using minimum margin protection price %.4f instead of %.4f for product %s (%.4f + %.4f%%)',
                        $minimumPrice,
                        $newPrice,
                        $product->getId(),
                        $cost,
                        $minimumMarginPercentage,
                    ),
                );
                $newPrice = $minimumPrice;
            }
        }

        $maximumListPriceDiscountPercentage = $this->getMaximumListPriceDiscountPercentage();
        if ($maximumListPriceDiscountPercentage !== null) {
            if (($listPrice = (float) $product->getListPrice()) < 0.01) {
                $this->logger->warning(
                    sprintf(
                        'Maximum list price discount enabled, but no list price found for product %s',
                        $product->getId(),
                    ),
                );
            } else {
                $maximumDiscount = $maximumListPriceDiscountPercentage;
                $minimumPrice = round($listPrice * (1 - $maximumDiscount / 100), 2);
                if ($newPrice < $minimumPrice) {
                    $this->logger->info(
                        sprintf(
                            'Using maximum list price discount price %.4f instead of %.4f for product %s (%.4f - %.4f%%)',
                            $minimumPrice,
                            $newPrice,
                            $product->getId(),
                            $listPrice,
                            $maximumDiscount,
                        ),
                    );
                    $newPrice = $minimumPrice;
                }
            }
        }

        if ($this->getRoundPrecision() > 0.01) {
            $roundedPrice = round($newPrice / $this->getRoundPrecision()) * $this->getRoundPrecision();
            if ($this->getRoundUp()) {
                if ($roundedPrice < $newPrice) {
                    $roundedPrice += $this->getRoundPrecision();
                }
            } else {
                if ($roundedPrice > $newPrice) {
                    $roundedPrice -= $this->getRoundPrecision();
                }
            }
            $this->logger->info(
                sprintf(
                    'Rounding price %.4f to precision %.4f for product %s: %.4f',
                    $newPrice,
                    $this->getRoundPrecision(),
                    $product->getId(),
                    $roundedPrice,
                ),
            );
            $newPrice = $roundedPrice;
        }

        $productPrice = $product->getPrice();
        if ($productPrice !== null && abs($productPrice - $newPrice) < 0.005) {
            $this->logger->debug(
                sprintf(
                    'Would adjust product %s price to %.2f according to %s, but it is already there',
                    $product->getId(),
                    $newPrice,
                    get_class($this->getPriceRule()),
                ),
            );
            return null;
        }

        $this->logger->info(
            sprintf(
                'Adjusting product %s price from %.2f to %.2f according to %s',
                $product->getId(),
                $product->getPrice() ?? '(none)',
                $newPrice,
                get_class($this->getPriceRule()),
            ),
        );

        return $newPrice;
    }
}
