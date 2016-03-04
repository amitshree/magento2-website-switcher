<?php

namespace Website\Switcher\Cookie;

use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\Cookie\PublicCookieMetadata;
use Magento\Framework\Stdlib\CookieManagerInterface;

class CustomerCountry
{
    /**
     * Name of cookie that holds private content version
     */
    const COUNTRY_CODE = 'IN';
    /**
     * CookieManager
     *
     * @var CookieManagerInterface
     */
    private $cookieManager;
    /**
     * @var CookieMetadataFactory
     */
    private $cookieMetadataFactory;
    /**
     * @var SessionManagerInterface
     */
    private $sessionManager;
    /**
     * @param CookieManagerInterface $cookieManager
     * @param CookieMetadataFactory $cookieMetadataFactory
     * @param SessionManagerInterface $sessionManager
     */
    public function __construct(
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        SessionManagerInterface $sessionManager
    ) {
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->sessionManager = $sessionManager;
    }
    /**
     * Get form key cookie
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->cookieManager->getCookie(self::COUNTRY_CODE);
    }
    /**
     * @param string $value
     * @param PublicCookieMetadata $metadata
     * @return void
     */
    public function setCountryCode($value)
    {
        $publicCookieMetadata = $this->cookieMetadataFactory->createPublicCookieMetadata()
            ->setDuration(1000)
            ->setPath('')
            ->setHttpOnly(true);

        $this->cookieManager->setPublicCookie(
            self::COUNTRY_CODE,
            $value,
            $publicCookieMetadata
        );
    }
    /**
     * @return void
     */
    public function deleteCountryCode()
    {
        $this->cookieManager->deleteCookie(
            self::COUNTRY_CODE,
            $this->cookieMetadataFactory
                ->createCookieMetadata()
                ->setPath($this->sessionManager->getCookiePath())
                ->setDomain($this->sessionManager->getCookieDomain())
        );
    }
}