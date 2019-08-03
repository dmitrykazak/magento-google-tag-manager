<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Test\Unit\Model\DataLayer;

use DK\GoogleTagManager\Model\DataLayer\Dto;
use DK\GoogleTagManager\Model\DataLayer\Generator;
use DK\GoogleTagManager\Model\DataLayer\ProductView;
use DK\GoogleTagManager\Model\UnsetProperty;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * @internal
 * @coversNothing
 */
final class ProductViewTest extends TestCase
{
    use UnsetProperty;

    /**
     * @var Generator\Product|MockObject
     */
    private $productGenerator;

    /**
     * @var ProductView
     */
    private $productView;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productGenerator = $this->createMock(Generator\Product::class);

        $this->productView = new ProductView($this->productGenerator);
    }

    public function testGetLayer(): void
    {
        $this->productGenerator
            ->expects(self::once())
            ->method('generate')
            ->with(null)
            ->willReturn($this->generateProductDto());

        $result = $this->productView->getLayer();

        $json = <<<'JSON'
{
  "ecommerce": {
    "detail": {
      "actionField": {"list": "Apparel"},
      "products": [{
        "name": "Triblend Android T-Shirt",
        "id": "12345",
        "price": "15.25",
        "brand": "Google",
        "category": "Apparel",
        "path": "Apparel/Android"
       }]
     }
   }
}
JSON;

        $this->assertJsonStringEqualsJsonString($json, \json_encode($result));
    }

    private function generateProductDto(): Dto\Product
    {
        $productDto = new Dto\Product();
        $productDto->id = '12345';
        $productDto->name = 'Triblend Android T-Shirt';
        $productDto->price = '15.25';
        $productDto->category = 'Apparel';
        $productDto->path = 'Apparel/Android';
        $productDto->brand = 'Google';

        $this->unset($productDto, ['variant']);

        return $productDto;
    }
}
