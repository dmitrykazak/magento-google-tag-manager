<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;
use DK\GoogleTagManager\Model\Session as SessionManager;
use Magento\Checkout\Model\Session;

class CartView implements DataLayerInterface
{
    public const CODE = 'cart-view';

    private const STEP = 1;
    private const OPTION = 'cart';

    /**
     * @var Session
     */
    private $session;

    /**
     * @var SessionManager
     */
    private $sessionManager;

    /**
     * @var Generator\CheckoutStep
     */
    private $checkoutStep;

    /**
     * @var Generator\Product
     */
    private $productGenerator;

    public function __construct(
        Session $session,
        SessionManager $sessionManager,
        Generator\CheckoutStep $checkoutStep,
        Generator\Product $productGenerator
    ) {
        $this->session = $session;
        $this->sessionManager = $sessionManager;
        $this->checkoutStep = $checkoutStep;
        $this->productGenerator = $productGenerator;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return static::CODE;
    }

    public function getLayer(): Dto\Ecommerce
    {
        return $this->checkoutStep->onCheckoutStep(self::STEP, self::OPTION);
    }

    public function getCartLayer(): array
    {
        $products = [];
        foreach ($this->session->getQuote()->getAllVisibleItems() as $item) {
            $products[] = $this->productGenerator->generate($item->getProduct(), $item);
        }

        return $products;
    }

    public function getRemoveCartLayer(): array
    {
        return $this->sessionManager->getRemovedProductFromCart(true);
    }
}
