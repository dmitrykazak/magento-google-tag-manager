<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer\Dto;

use DK\GoogleTagManager\Model\DataLayer\Dto\Cart\CheckoutOption;

final class Ecommerce
{
    /**
     * @var string
     */
    public $event;

    /**
     * @var array|CheckoutOption
     */
    public $ecommerce;
}
