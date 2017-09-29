<?php
class HP_Hepsipay_Block_Form_Checkout extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('hepsipay/checkout.phtml');
    }

    protected function _getConfig()
    {
        return Mage::getSingleton('payment/config');  
    }


    public function getQuote()
    {
        return Mage::getModel('checkout/session')->getQuote();
    }

    public function getCurrencyCode()
    {
        return Mage::app()->getStore()->getCurrentCurrencyCode();
    }

    public function getCurrencySymbole()
    {
        $code = $this->getCurrencyCode();
        return Mage::app()->getLocale()->currency($code)->getSymbol();
    }

    /**
     * Retrieve card expire months
     *
     * @return array
     */
    public function getTotal()
    {
        
        $quote = Mage::getModel('checkout/session')->getQuote();
        $totals = $quote->getTotals();
        $grand_total = $totals['grand_total']->getData('value');
        return $grand_total;
    }

    public function getCcMonths()
    {
        $months = $this->getData('cc_months');
        if (is_null($months)) {
            $months[0] =  $this->__('Month');
            $monthsFromConfig = $this->_getConfig()->getMonths();
            foreach($monthsFromConfig as $keyInLoop=>$monthInLoop){
                $monthNewText = (strlen($keyInLoop) == 1)?'0'.$keyInLoop:$keyInLoop;
                $monthsFromConfig[$keyInLoop] = $monthNewText;
            }
            $months = array_merge($months, $monthsFromConfig);
            $this->setData('cc_months', $months);
        }
        return $months;
    }

    /**
     * Retrieve card expire years
     *
     * @return array
     */
    public function getCcYears()
    {
        $years = $this->getData('cc_years');
        if (is_null($years)) {
            $years = $this->_getConfig()->getYears();
			$N = count($years);
			if($N < 15) {
				$y = array_pop((array_slice($years, -1)));
				for($i= 0; $i<=15 - $N;$i++) {
					$years[$y + $i] = $y+$i;
				}
			}
            $years = array(0=>$this->__('Year'))+$years;
            $this->setData('cc_years', $years);
        }
        return $years;
    }
    
    
    /**
     * Retrive has verification configuration
     *
     * @return boolean
     */
    public function hasVerification()
    {
        if ($this->getMethod()) {
            $configData = $this->getMethod()->getConfigData('useccv');
            if(is_null($configData)){
                return true;
            }
            return (bool) $configData;
        }
        return true;
    }
  
}