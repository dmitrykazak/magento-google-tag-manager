<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model;

use DK\GoogleTagManager\Model\DataLayer\Dto;
use Magento\Framework\Session\SessionManager;

class Session extends SessionManager
{
    private const KEY_REMOVED_PRODUCTS_FROM_CART = 'removed_products_from_cart';
    private const KEY_CHECKOUT_STEPS = 'checkout_step';

    public function setRemovedProductFromCart($product): self
    {
        $items = $this->getRemovedProductFromCart();

        $this->storage->setData(self::KEY_REMOVED_PRODUCTS_FROM_CART, \array_merge($items, [$product]));

        return $this;
    }

    public function getRemovedProductFromCart(bool $clear = false): array
    {
        $products = $this->getData(self::KEY_REMOVED_PRODUCTS_FROM_CART, $clear);

        return 0 < \count($products) ? $products : [];
    }

    public function setCheckoutStep(Dto\Ecommerce $paymentStepCheckout): self
    {
        $steps = $this->getCheckoutSteps();

        $this->storage->setData(self::KEY_CHECKOUT_STEPS, \array_merge($steps, [$paymentStepCheckout]));

        return $this;
    }

    public function getCheckoutSteps(bool $clear = false): array
    {
        $steps = $this->getData(self::KEY_CHECKOUT_STEPS, $clear);

        return 0 < \count($steps) ? $steps : [];
    }
}
