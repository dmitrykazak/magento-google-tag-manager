<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Escaper;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Sales\Model\Order\Item;
use DK\GoogleTagManager\Model\Handler\Product as ProductHandler;
use Magento\Framework\Serialize\SerializerInterface;

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
     * @var Escaper
     */
    private $escaper;

    /**
     * @var ProductHandler
     */
    private $productHandler;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(
        CheckoutSession $checkoutSession,
        PriceCurrencyInterface $priceCurrency,
        Escaper $escaper,
        ProductHandler $productHandler,
        SerializerInterface $serializer
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->priceCurrency = $priceCurrency;
        $this->escaper = $escaper;
        $this->productHandler = $productHandler;
        $this->serializer = $serializer;
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
        $order = $this->checkoutSession->getLastRealOrder();

        $purchaseOrderDto = new Purchase\Dto\Order();
        $productItems = [];

        /** @var Item $item */
        foreach ($order->getAllVisibleItems() as $item) {
            $this->productHandler->setProduct($item->getProduct());

            $productDto = new Dto\Product();
            $productDto->id = $item->getData($this->productHandler->productIdentifier());
            $productDto->name = $this->escaper->escapeJs($item->getName());
            $productDto->price = $this->priceCurrency->format($item->getPrice(), false, 2);
            $productDto->quantity = $item->getQtyOrdered();
            $productDto->category = $this->escaper->escapeJs($this->productHandler->getCategoriesPath());
            $productDto->brand = $this->productHandler->getBrandValue();

            $productItems[] = $productDto;
        }

        $purchaseOrderDto->id = $order->getIncrementId();
        $purchaseOrderDto->affiliation = $this->escaper->escapeJs($order->getStore()->getFrontendName());
        $purchaseOrderDto->total = $order->getBaseGrandTotal();
        $purchaseOrderDto->tax = $order->getBaseTaxAmount();
        $purchaseOrderDto->shipping = $order->getBaseShippingAmount();
        $purchaseOrderDto->coupon = $order->getCouponCode();
        $purchaseOrderDto->products = $productItems;

        $ecommerce = new Dto\Purchase\Ecommerce();
        $ecommerce->event = 'gtm.orderPurchase';
        $ecommerce->ecommerce = $purchaseOrderDto;

        return $this->getVariables();
    }
}
