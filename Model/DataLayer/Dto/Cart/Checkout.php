<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer\Dto\Cart;

use DK\GoogleTagManager\Model\DataLayer\Dto\Product\Product;

final class Checkout
{
    /**
     * @var Product
     */
    public $checkout;

    /**
     * @var null|string
     */
    public $currencyCode;
}
