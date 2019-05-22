<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\CustomerData;

use Magento\Customer\CustomerData\SectionPoolInterface;

class DataLayer implements SectionPoolInterface
{
    /**
     * {@inheritdoc}
     */
    public function getSectionsData(array $sectionNames = null, $updateIds = false)
    {
        return [
            'cart' => 'test',
        ];
    }
}
