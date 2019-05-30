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
    /**
     * @var \DK\GoogleTagManager\Model\Session
     */
    private $sessionManager;

    public function __construct(Session $session, \DK\GoogleTagManager\Model\Session $sessionManager, Generator\Product $productGenerator)
    {
        $this->session = $session;
        $this->productGenerator = $productGenerator;
        $this->sessionManager = $sessionManager;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return static::CODE;
    }

    public function getLayer(): array
    {
        $products = [];
        foreach ($this->session->getQuote()->getAllVisibleItems() as $item) {
            $products[] = $this->productGenerator->generate($item->getProduct(), $item);
        }

        return $products;
    }

    public function getRemoveCartLayer(): array
    {
        $product = $this->sessionManager->getRemovedProductFromCart(true);

        if (null === $product) {
            return [];
        }

        return $this->sessionManager->getRemovedProductFromCart(true);
    }
}
