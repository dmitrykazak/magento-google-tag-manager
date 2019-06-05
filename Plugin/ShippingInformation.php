<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Plugin;

use DK\GoogleTagManager\Model\DataLayer\Generator\CheckoutOptionStep;
use DK\GoogleTagManager\Model\Session;
use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Model\ShippingInformationManagement;
use Magento\Quote\Api\CartRepositoryInterface;

final class ShippingInformation
{
    private const SHIPPING_STEP = 3;

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
        ShippingInformationManagement $object,
        $result,
        $cartId,
        ShippingInformationInterface $addressInformation
    ) {
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->quoteRepository->getActive($cartId);

        $this->session->setCheckoutStep(
            $this->checkoutOptionStep->onCheckoutOptionStep(self::SHIPPING_STEP, $quote->getShippingAddress()->getShippingDescription())
        );

        return $result;
    }
}
