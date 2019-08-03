<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Test\Unit\Model\DataLayer;

use DK\GoogleTagManager\Model\DataLayer\Dto;
use DK\GoogleTagManager\Model\DataLayer\Generator;
use DK\GoogleTagManager\Model\DataLayer\PurchaseView;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Item;
use Magento\Store\Model\Store;
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
        $this->productGenerator = $this->createMock(Generator\Product::class);

        $this->purchaseView = new PurchaseView(
            $this->checkoutSession,
            $this->productGenerator
        );
    }

    public function testGetLayer(): void
    {
        /** @var MockObject|Product $product */
        $product = $this->createMock(Product::class);
        /** @var Category|MockObject $category */
        $category = $this->createMock(Category::class);

        $product->method('getCategory')->willReturn($category);
        $product->method('getData')->with('sku')->willReturn('12345');
        $product->method('getSpecialPrice')->willReturn(10.0);
        $product->method('getName')->willReturn('Triblend Android T-Shirt');

        /** @var MockObject|Order $order */
        $order = $this->createMock(Order::class);

        /** @var Item|MockObject $item */
        $item = $this->createMock(Item::class);
        $item->expects(self::once())->method('getProduct')->willReturn($product);

        $order->method('getAllVisibleItems')->willReturn([$item]);
        $order->method('getIncrementId')->willReturn('T12345');
        $order->method('getGrandTotal')->willReturn(10.0);
        $order->method('getTaxAmount')->willReturn(2.0);
        $order->method('getShippingAmount')->willReturn(5);
        $order->method('getCouponCode')->willReturn('SUMMER_SALE');
        $order->method('getOrderCurrencyCode')->willReturn('USD');

        /** @var MockObject|Store $storeMock */
        $storeMock = $this->createMock(Store::class);
        $storeMock->method('getFrontendName')->willReturn('Online Store');
        $order->method('getStore')->willReturn($storeMock);

        $this->checkoutSession
            ->expects(self::once())
            ->method('getLastRealOrder')
            ->willReturn($order);

        $this->productGenerator->expects(self::once())->method('generate')->willReturn(
            $this->generateProductDto($product)
        );

        $json = <<<'JSON'
{
  "event": "purchase",
  "ecommerce": {
    "currencyCode": "USD",
    "purchase": {
      "actionField": {
        "id": "T12345",
        "affiliation": "Online Store",
        "revenue": "8",
        "tax": "2",
        "shipping": "5",
        "coupon": "SUMMER_SALE"
      },
      "products": [{
        "name": "Triblend Android T-Shirt",
        "id": "12345",
        "price": 10,
        "brand": "Google",
        "category": "Apparel",
        "quantity": 5,
        "path": "Apparel/Android",
        "variant": "Color:Red"
       }]
    }
  }
}
JSON;

        $result = $this->purchaseView->getLayer();

        $this->assertJsonStringEqualsJsonString($json, \json_encode($result));
    }

    private function generateProductDto(Product $product): Dto\Product
    {
        $productDto = new Dto\Product();
        $productDto->id = $product->getData('sku');
        $productDto->name = $product->getName();
        $productDto->price = $product->getSpecialPrice();
        $productDto->category = 'Apparel';
        $productDto->path = 'Apparel/Android';
        $productDto->brand = 'Google';
        $productDto->quantity = 5;
        $productDto->variant = 'Color:Red';

        return $productDto;
    }
}
