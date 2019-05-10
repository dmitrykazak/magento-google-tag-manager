<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Escaper;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Sales\Model\Order\Item;
use Magento\Store\Model\StoreManagerInterface;

class CheckoutSuccessView extends AbstractLayer implements DataLayerInterface
{
    public const CODE = 'checkout-success-view';
    /**
     * @var CheckoutSession
     */
    private $checkoutSession;
    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var Escaper
     */
    private $escaper;

    public function __construct(
        CheckoutSession $checkoutSession,
        PriceCurrencyInterface $priceCurrency,
        StoreManagerInterface $storeManager,
        Escaper $escaper
    ){
        $this->checkoutSession = $checkoutSession;
        $this->priceCurrency = $priceCurrency;
        $this->storeManager = $storeManager;
        $this->escaper = $escaper;
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
        $this->addVariable(static::ECOMMERCE_NAME, [
            static::PURCHASE => [
                static::ACTION_FIELD_NAME => [
                ],
                static::PRODUCTS_NAME => $this->getProductListOrder(),
            ],
        ]);
    }

    public function getProductListOrder()
    {
        $order = $this->checkoutSession->getLastRealOrder();

        $items = [];
        /** @var Item $item */
        foreach ($order->getAllVisibleItems() as $item) {
            $items['id'] = $this->escaper->escapeJs($item->getSku());
            $items['name'] = $this->escaper->escapeJs($item->getName());
            $items['price'] = $this->priceCurrency->format($item->getPrice(), false, 2);
            $items['quantity'] = $item->getQtyOrdered();
        }

        $transaction = [];
        $transaction['transactionId'] = $order->getIncrementId();

        $transaction['transactionAffiliation'] = $this->escaper->escapeJs($this->storeManager->getStore()->getFrontendName());
        $transaction['transactionTotal'] = $order->getBaseGrandTotal();
        $transaction['transactionTax'] = $order->getBaseTaxAmount();
        $transaction['transactionShipping'] = $order->getBaseShippingAmount();
        $transaction['transactionSubTotal'] = $order->getBaseSubtotal();
        $transaction['transactionCouponCode'] = $order->getCouponCode();
        $transaction['transactionDiscount'] = $order->getDiscountAmount();

        $transaction['transactionProducts'] = $items;
    }
}