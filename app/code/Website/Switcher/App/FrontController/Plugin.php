<?php

namespace Website\Switcher\App\FrontController;
require_once BP.'/app/code/Website/Switcher/lib/geoip.inc';
use Magento\Framework\Controller\ResultFactory;
use Magento\Store\Model\StoreManager;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\FrontControllerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Request\Http;
use Website\Switcher\Cookie\CustomerCountry;

class Plugin
{
    protected $resultRedirectFactory;

    public function __construct(
        ResultFactory   $resultFactory,
        StoreManager    $storeManager,
        Http            $requestHttp,
        CustomerCountry $customerCountry,
        UrlInterface    $urlInterface
    )  {
        $this->resultFactory =   $resultFactory;
        $this->storeManager  =   $storeManager;
        $this->requestHttp  =    $requestHttp;
        $this->customerCountry = $customerCountry;
        $this->urlInterface    = $urlInterface;
       }

    public function aroundDispatch(
        FrontControllerInterface $subject,
        callable $proceed,
        RequestInterface $request
    ) {
        $baseUrl = $this->storeManager->getStore()->getBaseUrl();
 
        $gi = geoip_open(BP.'/app/code/Website/Switcher/lib/GeoIP.dat',GEOIP_STANDARD);
        $ip = $_SERVER['REMOTE_ADDR'];

        $customer_country_code = geoip_country_code_by_addr($gi, $ip);

         if(!$this->customerCountry->getCountryCode()){
             $this->customerCountry->setCountryCode($customer_country_code);
         }
        $country_code = $this->customerCountry->getCountryCode();
        $currentUrl = $this->urlInterface->getCurrentUrl();
        $requestUri = $this->requestHttp->getRequestUri();

        $redirectUrl=$baseUrl."india".$requestUri;

        if((strtoupper($country_code) == "IN") && (strpos($currentUrl, $baseUrl.'india') == FALSE)){
                $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                $resultRedirect->setUrl($redirectUrl);
                return $resultRedirect;
            }

         else {
            return $proceed($request);
        }
    }
}
