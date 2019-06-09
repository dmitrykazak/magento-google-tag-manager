<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\CustomerData;

use DK\GoogleTagManager\Model\DataLayer\CartView;
use DK\GoogleTagManager\Model\Session;
use Magento\Customer\CustomerData\SectionSourceInterface;

final class AnalyzerData implements SectionSourceInterface
{
    /**
     * @var CartView
     */
    private $cartView;

    /**
     * @var Session
     */
    private $sessionManager;

    public function __construct(CartView $cartView, Session $sessionManager)
    {
        $this->cartView = $cartView;
        $this->sessionManager = $sessionManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getSectionData(): array
    {
        return [
            'cart' => $this->cartView->getCartLayer(),
            'removeCart' => $this->cartView->getRemoveCartLayer(),
            'checkoutSteps' => $this->sessionManager->getCheckoutSteps(true),
        ];
    }
}
