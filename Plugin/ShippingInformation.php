<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Plugin;

use DK\GoogleTagManager\Model\DataLayer\Generator\CheckoutOptionStep;
use DK\GoogleTagManager\Model\Session;
use Magento\Quote\Api\CartRepositoryInterface;

final class ShippingInformation
{
    private const SHIPPINH_STEP = 3;

    /**
     * @var CartRepositoryInterface
     */
    private $quoteRepository;
    /**
     * @var Session
     */
    private $session;
    /**
     * @var CheckoutOptionStep
     */
    private $checkoutOptionStep;

    public function __construct(
        CartRepositoryInterface $quoteRepository,
        Session $session,
        CheckoutOptionStep $checkoutOptionStep
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->session = $session;
        $this->checkoutOptionStep = $checkoutOptionStep;
    }

    public function afterSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $object,
        $result,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) {
        $this->session->setCheckoutStep(
            $this->checkoutOptionStep->onCheckoutOptionStep(self::SHIPPINH_STEP, $addressInformation->getShippingDescription())
        );

        return $result;
    }
}
