<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer\Dto\Impression;

final class ImpressionProduct
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
    public $list;

    /**
     * @var int
     */
    public $position;
}
