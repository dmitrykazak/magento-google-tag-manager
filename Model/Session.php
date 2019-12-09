<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model;

use DK\GoogleTagManager\Model\DataLayer\Dto;
use Magento\Framework\Session\SessionManager;

class Session extends SessionManager
{
    private const KEY_REMOVED_PRODUCTS_FROM_CART = 'removed_products_from_cart';
    private const KEY_CHECKOUT_STEPS = 'checkout_step';
    private const KEY_IMPRESSION_CATALOG_PRODUCTS = 'impression_catalog_products';
    private const KEY_IMPRESSION_SEARCH_PRODUCTS = 'impression_search_products';
    private const KEY_CLICK_IMPRESSION_PRODUCTS = 'click_impression_products';

    public function setRemovedProductFromCart($product): Session
    {
        $items = $this->getRemovedProductFromCart();

        $this->storage->setData(self::KEY_REMOVED_PRODUCTS_FROM_CART, \array_merge($items, [$product]));

        return $this;
    }

    public function getRemovedProductFromCart(bool $clear = false): array
    {
        $products = $this->getData(self::KEY_REMOVED_PRODUCTS_FROM_CART, $clear);

        if (null === $products) {
            return [];
        }

        if (!\is_array($products) || !($products instanceof \Countable)) {
            return  [];
        }

        return 0 < \count($products) ? $products : [];
    }

    public function setCheckoutStep(Dto\Ecommerce $paymentStepCheckout): Session
    {
        $steps = $this->getCheckoutSteps();

        $this->storage->setData(self::KEY_CHECKOUT_STEPS, \array_merge($steps, [$paymentStepCheckout]));

        return $this;
    }

    public function getCheckoutSteps(bool $clear = false): array
    {
        $steps = $this->getData(self::KEY_CHECKOUT_STEPS, $clear);

        if (null === $steps) {
            return [];
        }

        if (!\is_array($steps) || !($steps instanceof \Countable)) {
            return  [];
        }

        return 0 < \count($steps) ? $steps : [];
    }

    public function setImpressionCatalogProducts($product): Session
    {
        $items = $this->getImpressionCatalogProducts();

        $this->storage->setData(self::KEY_IMPRESSION_CATALOG_PRODUCTS, \array_merge($items, [$product]));

        return $this;
    }

    public function getImpressionCatalogProducts(bool $clear = false): array
    {
        $items = $this->getData(self::KEY_IMPRESSION_CATALOG_PRODUCTS, $clear);

        if (null === $items) {
            return [];
        }

        if (!\is_array($items) || !($items instanceof \Countable)) {
            return  [];
        }

        return 0 < \count($items) ? $items : [];
    }

    public function setImpressionSearchProducts($product): Session
    {
        $items = $this->getImpressionSearchProducts();

        $this->storage->setData(self::KEY_IMPRESSION_SEARCH_PRODUCTS, \array_merge($items, [$product]));

        return $this;
    }

    public function getImpressionSearchProducts(bool $clear = false): array
    {
        $items = $this->getData(self::KEY_IMPRESSION_SEARCH_PRODUCTS, $clear);

        if (null === $items) {
            return [];
        }

        if (!\is_array($items) || !($items instanceof \Countable)) {
            return  [];
        }

        return 0 < \count($items) ? $items : [];
    }

    public function setClickImpressionProducts($product): Session
    {
        $items = $this->getClickImpressionProducts();

        $this->storage->setData(self::KEY_CLICK_IMPRESSION_PRODUCTS, \array_merge($items, [$product]));

        return $this;
    }

    public function getClickImpressionProducts(bool $clear = false): array
    {
        $items = $this->getData(self::KEY_CLICK_IMPRESSION_PRODUCTS, $clear);

        if (null === $items) {
            return [];
        }

        if (!\is_array($items) || !($items instanceof \Countable)) {
            return  [];
        }

        return 0 < \count($items) ? $items : [];
    }
}
