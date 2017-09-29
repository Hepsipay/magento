<?php

namespace Hepsipay\Hepsipay\Model;
use Hepsipay\Hepsipay\Helper\Hepsipayapi;

class HepsipayConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface
{
	const CODE = 'hepsipay';

    protected $method;

    /**
     * Payment ConfigProvider constructor.
     * @param \Magento\Payment\Helper\Data $paymentHelper
     */
    public function __construct(
        // \Magento\Payment\Helper\Data $paymentHelper,
        Hepsipayapi $helper
    ) {
        // $this->method = $paymentHelper->getMethodInstance(self::CODE);
        $this->helper = $helper;
    }
	/**
 	* {@inheritdoc}
	*/
    public function getConfig()
    {
    	 $config = ['payment' => ['hepsipay' => [
    	 'threed_secure' => $this->get3DSecure(),
    	 'force_threed_secure' => $this->getForce3DSecure(),
    	 'installment_commission' => $this->getCommissionStatus(),
    	 'bkm_express' => $this->getBKM(),
         'minimum_order' => $this->getMinOrderTotal(),
         'maximum_order' => $this->getMaxOrderTotal(),
         'installment' => $this->getInstallment()
    	 ]]];
        return $config;
    	// echo "sssssssssss";exit;
        // return $this->method->isAvailable() ? ['payment' => ['hepsipay' => ['threed_secure' => $this->get3DSecure()],],] : [];

        // return [
            // 'key' =&gt; 'value' pairs of configuration
        // ];
    }
    /**
	* Get config from admin
	*/
	public function get3DSecure()
	{
	    return $this->helper->get3DSecure();
	}
    public function getForce3DSecure()
    {
        return $this->helper->getForce3DSecure();
    }
    public function getCommissionStatus()
    {
        return $this->helper->getCommissionStatus();
    }
    public function getMinOrderTotal()
    {
        return $this->helper->getMinOrderTotal();
    }

    public function getMaxOrderTotal()
    {
        return $this->helper->getMaxOrderTotal();
    }
	
	public function getBKM()
	{
	    return $this->helper->getBKM();
	}
    public function getInstallment()
    {
        return $this->helper->getInstallment();
    }
}