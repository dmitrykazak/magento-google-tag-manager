<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\Handler\Click;

use DK\GoogleTagManager\Model\DataLayer\Generator\ClickImpression;
use DK\GoogleTagManager\Model\Session;
use Magento\Catalog\Model\Product;

class ClickCrossSellsImpressionHandler implements ClickImpressionHandlerInterface
{
    private const LIST = 'Cross Sells Products';

    /**
     * @var Session
     */
    private $session;

    /**
     * @var ClickImpression
     */
    private $clickImpressionGenerator;

    public function __construct(Session $session, ClickImpression $clickImpressionGenerator)
    {
        $this->session = $session;
        $this->clickImpressionGenerator = $clickImpressionGenerator;
    }

    public function handle(Product $product): void
    {
        $this->session->setClickImpressionProducts(
            $this->clickImpressionGenerator->generate($product, self::LIST)
        );
    }
}
