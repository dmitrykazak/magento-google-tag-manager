<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;
use Magento\Checkout\Model\Session;

class CartView implements DataLayerInterface
{
    public const CODE = 'cart-view';

    /**
     * @var Session
     */
    private $session;

    /**
     * @var Generator\Product
     */
    private $productGenerator;

    public function __construct(Session $session, Generator\Product $productGenerator)
    {
        $this->session = $session;
        $this->productGenerator = $productGenerator;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return static::CODE;
    }

    /**
     * @return object
     */
    public function getLayer()
    {
        $products = [];
        foreach ($this->session->getQuote()->getAllVisibleItems() as $item) {
            $products[] = $this->productGenerator->generate($item->getProduct());
        }

        return $products;
    }
}
