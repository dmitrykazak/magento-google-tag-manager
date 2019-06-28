<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Test\Unit\Model\DataLayer\Builder;

use DK\GoogleTagManager\Model\DataLayer\Builder\ImpressionBuilder;
use DK\GoogleTagManager\Model\DataLayer\Dto;
use DK\GoogleTagManager\Model\Handler;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * @internal
 * @coversNothing
 */
final class ImpressionBuilderTest extends TestCase
{
    /**
     * @var Handler\Product|MockObject
     */
    private $productHandler;

    /**
     * @var CategoryRepositoryInterface|MockObject
     */
    private $categoryRepository;

    /**
     * @var ImpressionBuilder
     */
    private $impressionBuilder;

    protected function setUp()
    {
        parent::setUp();

        $this->productHandler = $this->createMock(Handler\Product::class);
        $this->categoryRepository = $this->createMock(CategoryRepositoryInterface::class);

        $this->impressionBuilder = new ImpressionBuilder(
            $this->productHandler,
            $this->categoryRepository
        );
    }

    public function testBuild(): void
    {
        /** @var MockObject|Product $product */
        $product = $this->createMock(Product::class);
        /** @var Category|MockObject $category */
        $category = $this->createMock(Category::class);

        $product->method('getCategory')->willReturn($category);
        $product->method('getData')->with('sku')->willReturn('ART1');
        $product->method('getSpecialPrice')->willReturn(10.0);
        $product->method('getName')->willReturn('Test');

        $this->productHandler->method('productIdentifier')
            ->willReturn('sku');

        $this->productHandler->method('getProductPosition')->willReturn(1);
        $this->productHandler->method('getCategoryName')->willReturn('Apparel');
        $this->productHandler->method('getCategoriesPath')->willReturn('Apparel/Android');
        $this->productHandler->method('getBrandValue')->willReturn('Google');

        $result = $this->impressionBuilder->build($product, 'Search Catalog');

        $this->assertSame((array) $this->impressionObject($product, 'Search Catalog'), (array) $result);
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
