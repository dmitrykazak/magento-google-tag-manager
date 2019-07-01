<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Test\Unit\Model\DataLayer\Impressions;

use DK\GoogleTagManager\Model\DataLayer\Dto;
use DK\GoogleTagManager\Model\DataLayer\Generator\Impression;
use DK\GoogleTagManager\Model\DataLayer\Impressions\ProductRelatedView;
use DK\GoogleTagManager\Model\Handler\ProductHandler;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * @internal
 * @coversNothing
 */
final class ProductRelatedViewTest extends TestCase
{
    /**
     * @var Impression|MockObject
     */
    private $impressionGenerator;

    /**
     * @var MockObject|StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var MockObject|ProductHandler
     */
    private $productHandler;

    /**
     * @var ProductRelatedView
     */
    private $productRelatedView;

    protected function setUp()
    {
        parent::setUp();

        $this->impressionGenerator = $this->createMock(Impression::class);
        $this->storeManager = $this->createMock(StoreManagerInterface::class);
        $this->productHandler = $this->createMock(ProductHandler::class);

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

        $this->productRelatedView = new ProductRelatedView(
            $this->impressionGenerator,
            $this->storeManager,
            $this->productHandler
        );
    }

    public function testGetLayer(): void
    {
        /** @var MockObject|Product $product */
        $product = $this->createMock(Product::class);

        /** @var Category|MockObject $category */
        $category = $this->createMock(Category::class);

        $product->method('getCategory')->willReturn($category);
        $product->method('getData')->with('sku')->willReturn('ART1');
        $product->method('getSpecialPrice')->willReturn(10.0);
        $product->method('getName')->willReturn('Test');

        $product->method('getRelatedProducts')->willReturn([$product]);

        $this->productHandler->method('getProduct')->willReturn($product);

        $this->impressionGenerator
            ->expects(self::once())
            ->method('generate')
            ->willReturn($this->impressionObject($product, 'Related Products'));

        $json = <<<'JSON'
{
  "ecommerce": {
    "currencyCode": "USD",
    "impressions": [
     {
       "name": "Test",
       "id": "ART1",
       "price": "10",
       "brand": "Google",
       "category": "Apparel",
       "list": "Related Products",
       "path": "Apparel/Android",
       "position": 1
     }]
  }
}
JSON;

        $result = $this->productRelatedView->getLayer();

        $this->assertJsonStringEqualsJsonString($json, \json_encode($result));
    }

    private function impressionObject(Product $product, string $list): Dto\Impression\ImpressionProduct
    {
        $productImpressionDto = new Dto\Impression\ImpressionProduct();

        $productImpressionDto->id = $product->getData('sku');
        $productImpressionDto->name = $product->getName();
        $productImpressionDto->price = '10';
        $productImpressionDto->category = 'Apparel';
        $productImpressionDto->brand = 'Google';
        $productImpressionDto->path = 'Apparel/Android';
        $productImpressionDto->list = $list;
        $productImpressionDto->position = 1;

        return $productImpressionDto;
    }
}
