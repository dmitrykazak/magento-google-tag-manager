<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\CustomerData;

use DK\GoogleTagManager\Model\DataLayer\CartView;
use Magento\Customer\CustomerData\SectionSourceInterface;

class AnalyzerData implements SectionSourceInterface
{
    /**
     * @var CartView
     */
    private $cartView;

    public function __construct(CartView $cartView)
    {
        $this->cartView = $cartView;
    }

    /**
     * {@inheritdoc}
     */
    public function getSectionData()
    {
        return [
            'cart' => $this->cartView->getLayer(),
        ];
    }
}
