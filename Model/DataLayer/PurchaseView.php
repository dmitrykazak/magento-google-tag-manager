<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Sales\Model\Order\Item;

class PurchaseView implements DataLayerInterface
{
    public const CODE = 'purchase-view';

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;
    /**
     * @var Generator\Product
     */
    private $productGenerator;

    public function __construct(
        CheckoutSession $checkoutSession,
        PriceCurrencyInterface $priceCurrency,
        Generator\Product $productGenerator
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->priceCurrency = $priceCurrency;
        $this->productGenerator = $productGenerator;
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
        $order = $this->checkoutSession->getLastRealOrder();

        $purchaseOrderDto = new Dto\Purchase\Order();
        $productItems = [];

        /** @var Item $item */
        foreach ($order->getAllVisibleItems() as $item) {
            $productItems[] = $this->productGenerator->generate($item->getProduct());
        }

        $purchaseOrderDto->id = $order->getIncrementId();
        $purchaseOrderDto->affiliation = $order->getStore()->getFrontendName();
        $purchaseOrderDto->revenue = $order->getGrandTotal() - $order->getTaxAmount();
        $purchaseOrderDto->tax = $order->getTaxAmount();
        $purchaseOrderDto->shipping = $order->getShippingAmount();
        $purchaseOrderDto->coupon = $order->getCouponCode();
        $purchaseOrderDto->products = $productItems;

        $ecommerce = new Dto\Ecommerce();
        $ecommerce->event = 'gtm.orderPurchase';
        $ecommerce->ecommerce = $purchaseOrderDto;

        return $ecommerce;
    }
}
