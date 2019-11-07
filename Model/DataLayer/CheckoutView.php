<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;

class CheckoutView implements DataLayerInterface
{
    public const CODE = 'checkout-view';

    private const STEP = 2;
    private const OPTION = 'onCheckoutPage';

    /**
     * @var Generator\CheckoutStep
     */
    private $checkoutStep;

    public function __construct(Generator\CheckoutStep $checkoutStep)
    {
        $this->checkoutStep = $checkoutStep;
    }

    public function getCode(): string
    {
        return self::CODE;
    }

    public function getLayer(): Dto\Ecommerce
    {
        return $this->checkoutStep->onCheckoutStep(self::STEP, self::OPTION);
    }
}
