<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Test\Unit\Model\DataLayer\Generator;

use DK\GoogleTagManager\Model\DataLayer\Dto;
use DK\GoogleTagManager\Model\DataLayer\Generator;
use Magento\Checkout\Model\Session;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Item;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * @internal
 * @coversNothing
 */
final class CheckoutStepTest extends TestCase
{
    /**
     * @var MockObject|Session
     */
    private $checkoutSession;

    /**
     * @var MockObject|Quote
     */
    private $quote;

    /**
     * @var Generator\CheckoutStep
     */
    private $checkoutStep;

    /**
     * @var MockObject|StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Generator\Product|MockObject
     */
    private $generatorProduct;

    protected function setUp()
    {
        parent::setUp();

        $this->checkoutSession = $this->createMock(Session::class);
        $this->quote = $this->createMock(Quote::class);
        $this->storeManager = $this->createMock(StoreManagerInterface::class);
        $this->generatorProduct = $this->createMock(Generator\Product::class);

        $this->checkoutSession->method('getQuote')
            ->willReturn($this->quote);

        $this->checkoutStep = new Generator\CheckoutStep(
            $this->checkoutSession,
            $this->storeManager,
            $this->generatorProduct
        );
    }

    public function testOnCheckoutStep(): void
    {
        /** @var Item|MockObject $itemMock */
        $itemMock = $this->createMock(Item::class);

        /** @var MockObject|Store $storeMock */
        $storeMock = $this->createMock(Store::class);

        $this->storeManager->expects(self::once())
            ->method('getStore')
            ->willReturn($storeMock);

        $storeMock->expects($this->once())
            ->method('getCurrentCurrency')
            ->willReturn($storeMock);

        $storeMock->expects($this->once())
            ->method('getCode')
            ->willReturn('USD');

        $this->quote->method('getAllVisibleItems')
            ->willReturn([
                $itemMock,
            ]);

        $this->generatorProduct->method('generate')->willReturn($this->getProductDto());

        $itemMock->expects(self::once())->method('getProduct');

        $json = <<<'JSON'
{"event":"checkout","ecommerce":{"checkout":{"actionField":{"step":1,"option":"Visa"},"products":[{"id":12345,"name":"Triblend Android T-Shirt","price":"15.25","quantity":1,"category":"Apparel","brand":"Google","path":"Apparel\/Android"}]},"currencyCode":"USD"}}
JSON;

        $result = $this->checkoutStep->onCheckoutStep(1, 'Visa');

        $this->assertSame($json, \json_encode($result));
    }

    private function getProductDto(): Dto\Product
    {
        $product = new Dto\Product();

        $product->name = 'Triblend Android T-Shirt';
        $product->id = 12345;
        $product->price = '15.25';
        $product->brand = 'Google';
        $product->category = 'Apparel';
        $product->quantity = 1;
        $product->path = 'Apparel/Android';

        return $product;
    }
}
