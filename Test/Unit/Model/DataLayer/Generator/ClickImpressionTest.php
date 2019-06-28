<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Test\Unit\Model\DataLayer\Generator;

use DK\GoogleTagManager\Model\DataLayer\Builder\ImpressionBuilder;
use DK\GoogleTagManager\Model\DataLayer\Dto;
use DK\GoogleTagManager\Model\DataLayer\Generator\ClickImpression;
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
     * @var ImpressionBuilder|MockObject
     */
    private $impressionBuilder;

    /**
     * @var ClickImpression
     */
    private $clickImpression;

    protected function setUp()
    {
        parent::setUp();

        $this->impressionBuilder = $this->createMock(ImpressionBuilder::class);

        $this->clickImpression = new ClickImpression($this->impressionBuilder);
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

        $this->impressionBuilder
            ->expects(self::once())
            ->method('build')
            ->with($product, 'Search Catalog')
            ->willReturn($this->impressionObject($product, 'Search Catalog'));

        $this->clickImpression->generate($product, 'Search Catalog');
    }

    public function testGenerateJson(): void
    {
        /** @var MockObject|Product $product */
        $product = $this->createMock(Product::class);
        /** @var Category|MockObject $category */
        $category = $this->createMock(Category::class);

        $product->method('getCategory')->willReturn($category);
        $product->method('getData')->with('sku')->willReturn('ART1');
        $product->method('getSpecialPrice')->willReturn(10.0);
        $product->method('getName')->willReturn('Test');

        $this->impressionBuilder
            ->expects(self::once())
            ->method('build')
            ->with($product, 'Search Catalog')
            ->willReturn($this->impressionObject($product, 'Search Catalog'));

        $result = $this->clickImpression->generate($product, 'Search Catalog');

        $json = <<<'JSON'
{"event":"productClick","ecommerce":{"click":{"actionField":{"list":"Search Catalog"},"products":[{"id":"ART1","name":"Test","price":"10","category":"Apparel","brand":"Google","path":"Apparel\/Android","list":"Search Catalog","position":1}]}}}
JSON;

        $this->assertSame($json, \json_encode($result));
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
