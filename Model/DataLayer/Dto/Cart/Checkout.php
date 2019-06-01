<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer\Dto\Cart;

final class Checkout
{
    /**
     * @var Cart
     */
    public $checkout;

    /**
     * @var null|string
     */
    public $currencyCode;
}
