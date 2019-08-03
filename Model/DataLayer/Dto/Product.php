<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer\Dto;

final class Product
{
    /**
     * @var int|string
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var float
     */
    public $price;

    /**
     * @var null|float
     */
    public $quantity;

    /**
     * @var string
     */
    public $category;

    /**
     * @var string;
     */
    public $brand;

    /**
     * @var string
     */
    public $path;

    /**
     * @var string
     */
    public $variant;
}
