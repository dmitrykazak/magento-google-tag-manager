<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\Handler;

use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\Sales\Model\Order\Item as OrderItem;

class ItemHandler
{
    private const KEY_OPTIONS = [
        'options',
        'additional_options',
        'attributes_info',
    ];

    private const DELIMITER = ' | ';
    private const DELIMITER_OPTION = ':';

    /**
     * @param OrderItem|QuoteItem $item
     *
     * @return string
     */
    public function getVariant($item): string
    {
        $options = $this->collectOptions(
            $this->getOptions($item)
        );

        $result = [];

        foreach ($options as $option) {
            $selected = [];

            if (\is_array($options) && \array_key_exists('label', $option)) {
                $selected[] = $option['label'];
            }

            if (\is_array($options) && \array_key_exists('value', $option)) {
                $selected[] = $option['value'];
            }

            $result[] = \implode(self::DELIMITER_OPTION, $selected);
        }

        return \implode(self::DELIMITER, $result);
    }

    /**
     * @param OrderItem|QuoteItem $item
     *
     * @return array
     */
    private function getOptions($item): array
    {
        $options = [];

        if ($item instanceof OrderItem) {
            $options = $item->getProductOptions();
        } elseif ($item instanceof QuoteItem) {
            $options = $item->getProduct()->getTypeInstance()->getOrderOptions($item->getProduct());
        }

        return $options;
    }

    private function collectOptions(array $options): array
    {
        $result = [];

        foreach (self::KEY_OPTIONS as $keyOption) {
            if (isset($options[$keyOption])) {
                $result[] = $options[$keyOption];
            }
        }

        return 0 < \count($result) ? \array_merge(...$result) : [];
    }
}
