<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Test\Unit\Model\DataLayer\Generator;

use DK\GoogleTagManager\Model\DataLayer\Generator;
use DK\GoogleTagManager\Model\Handler;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\Quote\Model\Quote\Item;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * @internal
 * @coversNothing
 */
final class ProductTest extends TestCase
{
    /**
     * @var Handler\ProductHandler|MockObject
     */
    private $productHandler;

    /**
     * @varCategoryRepositoryInterface|MockObject
     */
    private $categoryRepository;

    /**
     * @var Generator\Product
     */
    private $productGenerator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productHandler = $this->createMock(Handler\ProductHandler::class);
        $this->categoryRepository = $this->createMock(CategoryRepositoryInterface::class);

        $this->productGenerator = new Generator\Product(
            $this->productHandler,
            $this->categoryRepository
        );
    }

    public function testGenerate(): void
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

        $this->productHandler->method('getProduct')->willReturn($product);

        $this->productHandler->method('getCategoryName')->willReturn('Apparel');
        $this->productHandler->method('getCategoriesPath')->willReturn('Apparel/Android');
        $this->productHandler->method('getBrandValue')->willReturn('Google');

        $result = $this->productGenerator->generate($product);

        $this->assertSame('Test', $result->name);
        $this->assertSame('Google', $result->brand);
        $this->assertSame('Apparel', $result->category);
        $this->assertSame('ART1', $result->id);
    }

    public function testGenerateItem(): void
    {
        /** @var Item|MockObject $itemMock */
        $itemMock = $this->createMock(Item::class);
        $itemMock->method('getQty')->willReturn(5);

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

        $this->productHandler->method('getProduct')->willReturn($product);

        $this->productHandler->method('getCategoryName')->willReturn('Apparel');
        $this->productHandler->method('getCategoriesPath')->willReturn('Apparel/Android');
        $this->productHandler->method('getBrandValue')->willReturn('Google');

        $result = $this->productGenerator->generate($product, $itemMock);

        $this->assertSame('Test', $result->name);
        $this->assertSame('Google', $result->brand);
        $this->assertSame('Apparel', $result->category);
        $this->assertSame('ART1', $result->id);
        $this->assertSame(5, $result->quantity);
    }
}
