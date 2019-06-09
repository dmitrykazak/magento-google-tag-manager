<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\CustomerData;

use Magento\Customer\CustomerData\SectionSourceInterface;

final class ImpressionData implements SectionSourceInterface
{
    /**
     * @inheritdoc
     */
    public function getSectionData(): array
    {
        return [];
    }
}
