<?php

class HP_Hepsipay_Block_Info_Payment extends Mage_Payment_Block_Info
{
     protected function _prepareSpecificInformation($transport = null)
    {
        if (null !== $this->_paymentSpecificInformation) {
            return $this->_paymentSpecificInformation;
        }
        $info = $this->getInfo();
        $transport = new Varien_Object(array(Mage::helper('payment')->__('Name on the Card') => $info->getCcOwner(),));
        $transport = parent::_prepareSpecificInformation($transport);
        
        
        $data = array();
        
        if ($this->getInfo()->getCcLast4()) {
            $data[Mage::helper('payment')->__('Card Number')] = sprintf('xxxx-%s', $this->getInfo()->getCcLast4());
        }
        
        // if ($this->getInfo()->getInstallment()) 
        // {
        //   $data[Mage::helper('payment')->__('Installments')] = $this->getInfo()->getInstallment();
        // }
        
        if ($this->getInfo()->getLastTransId()) 
        {
          $data[Mage::helper('payment')->__('Transaction ID')] = $this->getInfo()->getLastTransId();
        }
        
        return $transport->setData(array_merge($data, $transport->getData()));
    }
    
}