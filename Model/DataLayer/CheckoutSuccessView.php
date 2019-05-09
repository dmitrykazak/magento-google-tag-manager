<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
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

    public function __construct(CheckoutSession $checkoutSession, PriceCurrencyInterface $priceCurrency, StoreManagerInterface $storeManager)
    {
        $this->checkoutSession = $checkoutSession;
        $this->priceCurrency = $priceCurrency;
        $this->storeManager = $storeManager;
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
            static::DETAIL_NAME => [
                static::ACTION_FIELD_NAME => [
                    static::ACTON_PRODUCT_NAME => static::CODE
                ],
                static::PRODUCTS_NAME => [
                    'id' => 'test',
                    'name' => 'name',
                ],
            ],
        ]);
    }

    public function getCheck()
    {
        $order = $this->checkoutSession->getLastRealOrder();

        $items = [];
        /** @var Item $item */
        foreach ($order->getAllVisibleItems() as $item) {
            $items['name'] = $item->getName();
            $items['id'] = $item->getSku();
            $items['price'] = $this->priceCurrency->format($item->getBasePrice(), false, 2);
            $items['quantity'] = $item->getQtyOrdered();
        }

        $transaction = [];
        $transaction['transactionId'] = $order->getIncrementId();
        $transaction['transactionAffiliation'] = $this->storeManager->getStore()->getName();
        $transaction['transactionTotal'] = $order->getBaseGrandTotal();
        $transaction['transactionTax'] = $order->getBaseTaxAmount();
        $transaction['transactionShipping'] = $order->getBaseShippingAmount();
        $transaction['transactionSubTotal'] = $order->getBaseSubtotal();
        $transaction['transactionCouponCode'] = $order->getCouponCode();
        $transaction['transactionDiscount'] = $order->getDiscountAmount();

        $transaction['transactionProducts'] = $items;
    }
}