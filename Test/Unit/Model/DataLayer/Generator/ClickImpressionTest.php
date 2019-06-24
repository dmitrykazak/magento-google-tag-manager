<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Test\Unit\Model\DataLayer\Generator;

use DK\GoogleTagManager\Model\DataLayer\Generator\ClickImpression;
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
final class ClickImpressionTest extends TestCase
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
     * @var ClickImpression
     */
    private $clickImpression;

    protected function setUp()
    {
        parent::setUp();

        $this->productHandler = $this->createMock(Handler\Product::class);
        $this->categoryRepository = $this->createMock(CategoryRepositoryInterface::class);

        $this->clickImpression = new ClickImpression(
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

        $this->clickImpression->generate($product, 'Search Catalog');
    }
}
