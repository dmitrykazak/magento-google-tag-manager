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

    private const EVENT = 'purchase';

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

        $productItems = [];

        /** @var Item $item */
        foreach ($order->getAllVisibleItems() as $item) {
            $productItems[] = $this->productGenerator->generate($item->getProduct(), $item->getQtyOrdered());
        }

        $purchaseOrderDto = new Dto\Purchase\Order();
        $purchaseOrderDto->id = $order->getIncrementId();
        $purchaseOrderDto->affiliation = $order->getStore()->getFrontendName();
        $purchaseOrderDto->revenue = (string) ($order->getGrandTotal() - $order->getTaxAmount());
        $purchaseOrderDto->tax = (string) $order->getTaxAmount();
        $purchaseOrderDto->shipping = (string) $order->getShippingAmount();
        $purchaseOrderDto->coupon = $order->getCouponCode() ?: '';

        $purchaseDetailsDto = new Dto\EcommerceDetails();
        $purchaseDetailsDto->products = $productItems;
        $purchaseDetailsDto->actionField = $purchaseOrderDto;

        $purchaseDto = new Dto\Purchase\Purchase();
        $purchaseDto->purchase = $purchaseDetailsDto;
        $purchaseDto->currencyCode = $order->getOrderCurrencyCode();

        $ecommerce = new Dto\Ecommerce();
        $ecommerce->ecommerce = $purchaseDto;
        $ecommerce->event = self::EVENT;

        return $ecommerce;
    }
}
