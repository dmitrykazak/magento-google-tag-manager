<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer\Dto\Cart;

use DK\GoogleTagManager\Model\DataLayer\Dto\EcommerceDetails;

final class Checkout
{
    /**
     * @var EcommerceDetails
     */
    public $checkout;

    /**
     * @var null|string
     */
    public $currencyCode;
}
