<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer\Dto\Purchase;

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
    public $revenue;

    /**
     * @var null|float
     */
    public $tax;

    /**
     * @var null|float
     */
    public $shipping;

    /**
     * @var null|string
     */
    public $coupon;

    /**
     * @var Product[]
     */
    public $products;
}
