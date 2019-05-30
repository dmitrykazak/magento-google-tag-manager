<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model;

use Magento\Framework\Session\SessionManager;

class Session extends SessionManager
{
    public function setRemovedProductFromCart($product)
    {
        $this->storage->setData('removed_product_from_cart', $product);

        return $this;
    }

    public function getRemovedProductFromCart(bool $clear = false)
    {
        $product = $this->getData('removed_product_from_cart', $clear);

        return null !== $product ? $product : null;
    }
}
