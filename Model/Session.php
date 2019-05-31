<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model;

use Magento\Framework\Session\SessionManager;

class Session extends SessionManager
{
    private const KEY_REMOVED_PRODUCTS_FROM_CART = 'removed_products_from_cart';

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
}
