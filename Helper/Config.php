<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;

class Config extends AbstractHelper
{
    private const XML_PATH_ACTIVE = 'google/googletagmanager/active';
    private const XML_PATH_ACCOUNT = 'google/googletagmanager/account';

    private const XML_PATH_BRAND_ENABLE = 'google/googletagmanager/brand_enable';
    private const XML_PATH_BRAND_ATTRIBUTE = 'google/googletagmanager/brand_attribute';

    /**
     * @param null $store
     *
     * @return bool
     */
    public function isGoogleTagManagerAvailable($store = null): bool
    {
        $account = $this->getAccount($store);

        return $account && $this->scopeConfig->isSetFlag(
            self::XML_PATH_ACTIVE,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param null|string|bool|int|Store $store
     *
     * @return null|string
     */
    public function getAccount($store = null): ?string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_ACCOUNT,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function getEnableBrand($store = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_BRAND_ENABLE,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param null $store
     *
     * @return string|null
     */
    public function getBrandAttribute($store = null): ?string
    {
        if ($this->getEnableBrand($store)) {
            return $this->scopeConfig->getValue(
                self::XML_PATH_BRAND_ATTRIBUTE,
                ScopeInterface::SCOPE_STORE,
                $store
            );
        }

        return null;
    }
}