<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer;

abstract class AbstractLayer
{
    public const ECOMMERCE_NAME = 'ecommerce';
    public const DETAIL_NAME = 'detail';
    public const ACTION_FIELD_NAME = 'actionField';
    public const PRODUCTS_NAME = 'products';
    public const CATEGORY_NAME = 'category';
    public const ACTON_PRODUCT_NAME = 'list';
    public const PURCHASE = 'purchase';

    private $variables = [];

    public function addVariable($name, $value): void
    {
        $this->variables[$name] = $value ?: [];
    }

    public function getVariables(): array
    {
        return $this->variables;
    }
}