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

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    public function __construct(
        Session $session,
        \DK\GoogleTagManager\Model\Session $sessionManager,
        Generator\Product $productGenerator,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->session = $session;
        $this->productGenerator = $productGenerator;
        $this->sessionManager = $sessionManager;
        $this->storeManager = $storeManager;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return static::CODE;
    }

    public function getLayer(): Dto\Ecommerce
    {
        $products = [];
        foreach ($this->session->getQuote()->getAllVisibleItems() as $item) {
            $products[] = $this->productGenerator->generate($item->getProduct(), $item);
        }

        $actionField = new Dto\Cart\ActionField();
        $actionField->step = 1;
        $actionField->option = 'cart';

        $cart = new Dto\Cart\Cart();
        $cart->actionField = $actionField;
        $cart->products = $products;

        $checkout = new Dto\Cart\Checkout();
        $checkout->checkout = $cart;
        $checkout->currencyCode = $this->storeManager->getStore()->getCurrentCurrency()->getCode();

        $ecommerce = new Dto\Ecommerce();
        $ecommerce->event = 'checkout';
        $ecommerce->ecommerce = $checkout;

        return $ecommerce;
    }

    public function getCartLayer(): array
    {
        $products = [];
        foreach ($this->session->getQuote()->getAllVisibleItems() as $item) {
            $products[] = $this->productGenerator->generate($item->getProduct(), $item);
        }

        return $products;
    }

    public function getRemoveCartLayer(): array
    {
        return $this->sessionManager->getRemovedProductFromCart(true);
    }
}
