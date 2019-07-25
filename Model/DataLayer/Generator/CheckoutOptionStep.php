<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer\Generator;

use DK\GoogleTagManager\Model\DataLayer\Dto;
use DK\GoogleTagManager\Model\UnsetProperty;

class CheckoutOptionStep
{
    use UnsetProperty;

    private const EVENT = 'checkout';

    public function onCheckoutOptionStep(int $step, string $option): Dto\Ecommerce
    {
        $actionField = new Dto\Cart\ActionField();
        $actionField->step = $step;
        $actionField->option = $option;

        $optionActionField = new Dto\Cart\OptionActionFields();
        $optionActionField->actionField = $actionField;

        $checkoutOption = new Dto\Cart\Checkout();
        $checkoutOption->checkout = $optionActionField;

        $this->unset($checkoutOption, ['currencyCode']);

        $ecommerce = new Dto\Ecommerce();
        $ecommerce->event = self::EVENT;
        $ecommerce->ecommerce = $checkoutOption;

        return $ecommerce;
    }
}
