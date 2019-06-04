<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Plugin;

use DK\GoogleTagManager\Model\DataLayer\Generator\CheckoutOptionStep;
use DK\GoogleTagManager\Model\Session;
use Magento\Checkout\Model\GuestPaymentInformationManagement;
use Magento\Sales\Api\OrderRepositoryInterface;

final class GuestPaymentInformation
{
    private const PAYMENT_STEP = 4;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var CheckoutOptionStep
     */
    private $checkoutOptionStep;

    /**
     * @var Session
     */
    private $session;

    public function __construct(OrderRepositoryInterface $orderRepository, CheckoutOptionStep $checkoutOptionStep, Session $session)
    {
        $this->orderRepository = $orderRepository;
        $this->checkoutOptionStep = $checkoutOptionStep;
        $this->session = $session;
    }

    public function afterSavePaymentInformationAndPlaceOrder(GuestPaymentInformationManagement $subject, int $result): int
    {
        $order = $this->orderRepository->get($result);
        $payment = $order->getPayment();

        if (null === $payment) {
            return $result;
        }

        $information = $payment->getAdditionalInformation();

        if (0 === \count($information) || !isset($information['method_title'])) {
            return $result;
        }

        $this->session->setCheckoutStep(
            $this->checkoutOptionStep->onCheckoutOptionStep(self::PAYMENT_STEP, $information['method_title'])
        );

        return $result;
    }
}
