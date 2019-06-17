<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Observer;

use DK\GoogleTagManager\Model\Session;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

final class ControllerFrontSendResponseBeforeObserver implements ObserverInterface
{
    /**
     * @var RedirectInterface
     */
    private $redirect;

    /**
     * @var Session
     */
    private $session;

    public function __construct(RedirectInterface $redirect, Session $session)
    {
        $this->redirect = $redirect;
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Observer $observer): void
    {
        if (!$observer->getRequest()->isAjax()) {
            $this->session->setLastRefererUrl($this->redirect->getRefererUrl());
        }
    }
}
