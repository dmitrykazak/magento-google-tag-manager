<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;
use DK\GoogleTagManager\Model\Handler\Product as ProductHandler;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Sales\Model\Order\Item;

class PurchaseView extends AbstractLayer implements DataLayerInterface
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
     * @var ProductHandler
     */
    private $productHandler;

    public function __construct(
        CheckoutSession $checkoutSession,
        PriceCurrencyInterface $priceCurrency,
        ProductHandler $productHandler
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->priceCurrency = $priceCurrency;
        $this->productHandler = $productHandler;
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
            $this->productHandler->setProduct($item->getProduct());

            $productDto = new Dto\Product();
            $productDto->id = $item->getData($this->productHandler->productIdentifier());
            $productDto->name = $item->getName();
            $productDto->price = $this->priceCurrency->format($item->getPrice(), false, 2);
            $productDto->quantity = $item->getQtyOrdered();
            $productDto->category = $this->productHandler->getCategoryName();
            $productDto->path = $this->productHandler->getCategoriesPath();
            $productDto->brand = $this->productHandler->getBrandValue();

            $productItems[] = $productDto;
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
