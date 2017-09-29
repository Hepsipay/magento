<?php

class HP_Hepsipay_Model_Commission extends Mage_Sales_Model_Quote_Address_Total_Abstract {
    protected $_code = 'commission';

    public function __construct()
    {
        $this->setCode($this->_code);
    }

    public function collect(Mage_Sales_Model_Quote_Address $address) {
        parent::collect($address);

        $this->_setAmount(0);
        $this->_setBaseAmount(0);

        $quote = $address->getQuote();
        if (!$quote->isVirtual() && $address->getAddressType() == 'billing') {
            return $this;
        }

        if (isset($_REQUEST['payment']) && $_REQUEST['payment'] != '') {
            $exist_amount   = $quote->getFeeAmount();

            $fee            = $this->getCommission($_REQUEST["payment"]);
            $commission     = $fee - $exist_amount;
            $address->setFeeAmount($commission);
            $address->setBaseFeeAmount($commission);

            $quote->setFeeAmount($commission);

            $address->setGrandTotal($address->getGrandTotal() + $address->getFeeAmount());
            $address->setBaseGrandTotal($address->getBaseGrandTotal() + $address->getBaseFeeAmount());

        }

        return $this;
    }

    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        $amt = $address->getFeeAmount();
        $address->addTotal(array(
            'code'  => $this->getCode(),
            'title' => __('Commission'),
            'value' => $amt
        ));
        return $this;
    }

    public function getCommission($data) {
        if(!Mage::getStoreConfig('payment/hepsipay/use_commission')){
            return 0;
        }

        $quote          = Mage::getModel('checkout/session')->getQuote();
        $totals         = $quote->getTotals();
        $grand_total    = 0;

        foreach($totals as $key=>$total){
            if($key == 'grand_total') continue;
            $grand_total += $total->getData('value');
        }

        $paymentHelper = new HP_Hepsipay_Model_Payment;

        $banksInfo = $paymentHelper->banks();
        $banksInfo = $banksInfo['data'];

        if(!isset($data['bank_id'])){
            $binInfo   = $paymentHelper->bin($data['cc_number']);
            $binInfo   = $binInfo['data'];
        }else{
            $binInfo['bank_id'] = $data['bank_id'];
        }

        $installments_commission = 0;

        //todo: hepsipay - bkm

        foreach($banksInfo as $bank){
            if($bank['bank'] == $binInfo['bank_id']){
                foreach($bank['installments'] as $installment){
                    if($installment['count'] == $data['installment']){
                        $installments_commission = $installment['commission'];
                        $installments_commission = str_replace('%', '', $installments_commission);
                        break;
                    }
                }
            }
        }

        if($data['installment'] == 1){
            $installments_commission = $banksInfo[0]['installments'][0]['commission'];
            $installments_commission = str_replace('%', '', $installments_commission);
        }

        $subTotalValue = $grand_total * ($installments_commission/100);
        $subTotalValue = number_format($subTotalValue, 2, '.', '');
        return $subTotalValue;
    }
}
