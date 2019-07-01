<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Test\Unit\Model\DataLayer;

use DK\GoogleTagManager\Model\DataLayer\Generator;
use DK\GoogleTagManager\Model\DataLayer\PurchaseView;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Sales\Model\Order;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * @internal
 * @coversNothing
 */
final class PurchaseViewTest extends TestCase
{
    /**
     * @var CheckoutSession|MockObject
     */
    private $checkoutSession;

    /**
     * @var MockObject|PriceCurrencyInterface
     */
    private $priceCureency;

    /**
     * @var Generator\Product|MockObject
     */
    private $productGenerator;

    /**
     * @var PurchaseView
     */
    private $purchaseView;

    protected function setUp(): void
    {
        parent::setUp();

        $this->checkoutSession = $this->createMock(CheckoutSession::class);
        $this->priceCureency = $this->createMock(PriceCurrencyInterface::class);
        $this->productGenerator = $this->createMock(Generator\Product::class);

        $this->purchaseView = new PurchaseView(
            $this->checkoutSession,
            $this->priceCureency,
            $this->productGenerator
        );
    }

    public function testGetLayer(): void
    {
        /** @var MockObject|Order $order */
        $order = $this->createMock(Order::class);
        $order->method('getIncrementId')->willReturn('1000000001');
        $order->method('getGrandTotal')->willReturn(10.0);
        $order->method('getTaxAmount')->willReturn(2.0);

        $this->checkoutSession
            ->expects(self::once())
            ->method('getLastRealOrder')
            ->willReturn($order);

        $result = $this->purchaseView->getLayer();
    }
}
