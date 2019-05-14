<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer\Purchase\Dto;

use DK\GoogleTagManager\Model\DataLayer\Dto\Product;

final class Order
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $affiliation;

    /**
     * @var float
     */
    public $total;

    /**
     * @var float|null
     */
    public $tax;

    /**
     * @var float|null
     */
    public $shipping;

    /**
     * @var string|null
     */
    public $coupon;

    /**
     * @var Product[]
     */
    public $products;
}
