<?php

class HP_Hepsipay_Model_Api
{
    protected $_config;

    public function & getConfig()
    {
        if(empty($this->_config)) {
            $attrs = ['endpoint'];
            foreach ($attrs as $key) {
                $this->_config[$key] = Mage::getStoreConfig('payment/hepsipay/'.$key);
            }
        }
        return $this->_config;
    }

    public function test()
    {
        $config = $this->getConfig();
        $configData = $this->getMethod()->getData();
        return $configData;
    }
}
