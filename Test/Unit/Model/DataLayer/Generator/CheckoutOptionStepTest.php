<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Test\Unit\Model\DataLayer\Generator;

use DK\GoogleTagManager\Model\DataLayer\Dto;
use DK\GoogleTagManager\Model\DataLayer\Generator\CheckoutOptionStep;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class CheckoutOptionStepTest extends TestCase
{
    /**
     * @var CheckoutOptionStep
     */
    private $checkoutOptionStep;

    protected function setUp()
    {
        parent::setUp();

        $this->checkoutOptionStep = new CheckoutOptionStep();
    }

    public function testOnCheckoutOptionStep(): void
    {
        /** @var Dto\Ecommerce $result */
        $result = $this->checkoutOptionStep->onCheckoutOptionStep(1, 'Checkout');

        $ref = new \ReflectionClassConstant(CheckoutOptionStep::class, 'EVENT');

        $this->assertSame($ref->getValue(), $result->event);
        $this->assertSame('Checkout', $result->ecommerce->checkout_option->actionField->option);
    }

    public function testOnCheckoutOptionSchema(): void
    {
        $json = <<<'JSON'
{"event":"checkoutOption","ecommerce":{"checkout_option":{"actionField":{"step":1,"option":"Checkout"}}}}
JSON;
        /** @var Dto\Ecommerce $result */
        $result = $this->checkoutOptionStep->onCheckoutOptionStep(1, 'Checkout');

        $this->assertSame($json, \json_encode($result));
    }
}
