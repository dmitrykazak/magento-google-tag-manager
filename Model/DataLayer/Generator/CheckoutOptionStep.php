<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer\Generator;

use DK\GoogleTagManager\Model\DataLayer\Dto;

class CheckoutOptionStep
{
    private const  EVENT = 'checkoutOption';

    public function onCheckoutOptionStep(int $step, string $option): Dto\Ecommerce
    {
        $actionField = new Dto\Cart\ActionField();
        $actionField->step = $step;
        $actionField->option = $option;

        $checkoutOption = new Dto\Cart\CheckoutOption();
        $checkoutOption->checkout_option = $actionField;

        $ecommerce = new Dto\Ecommerce();
        $ecommerce->event = self::EVENT;
        $ecommerce->ecommerce = $checkoutOption;

        return $ecommerce;
    }
}
