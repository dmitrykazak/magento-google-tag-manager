<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Escaper;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Sales\Model\Order\Item;
use Magento\Store\Model\StoreManagerInterface;
use DK\GoogleTagManager\Model\Handler\Product as ProductHandler;

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
    /**
     * @var ProductHandler
     */
    private $productHandler;

    public function __construct(
        CheckoutSession $checkoutSession,
        PriceCurrencyInterface $priceCurrency,
        StoreManagerInterface $storeManager,
        Escaper $escaper,
        ProductHandler $productHandler
    ){
        $this->checkoutSession = $checkoutSession;
        $this->priceCurrency = $priceCurrency;
        $this->storeManager = $storeManager;
        $this->escaper = $escaper;
        $this->productHandler = $productHandler;
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

        $items = [];
        /** @var Item $item */
        foreach ($order->getAllVisibleItems() as $item) {
            $items['id'] = $item->getData($this->productHandler->productIdentifier());
            $items['name'] = $this->escaper->escapeJs($item->getName());
            $items['price'] = $this->priceCurrency->format($item->getPrice(), false, 2);
            $items['quantity'] = $item->getQtyOrdered();
            $items['category'] = $this->productHandler->getCategoryName();
            $items['brand'] = $this->productHandler->getBrandValue();
        }

        $transaction = [];
        $transaction['id'] = $order->getIncrementId();
        $transaction['affiliation'] = $this->escaper->escapeJs($this->storeManager->getStore()->getFrontendName());
        $transaction['total'] = $order->getBaseGrandTotal();
        $transaction['tax'] = $order->getBaseTaxAmount();
        $transaction['shipping'] = $order->getBaseShippingAmount();
        $transaction['coupon'] = $order->getCouponCode();

        $this->addVariable(static::ECOMMERCE_NAME, [
            static::PURCHASE => [
                static::ACTION_FIELD_NAME => $transaction,
                static::PRODUCTS_NAME => $items,
            ],
        ]);

        return $this->getVariables();
    }
}
