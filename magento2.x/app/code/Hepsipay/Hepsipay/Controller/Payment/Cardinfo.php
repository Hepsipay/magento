<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Hepsipay\Hepsipay\Controller\Payment;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Hepsipay\Hepsipay\Helper\Hepsipayapi;
use Hepsipay\Hepsipay\Model\HistoryFactory;
use Magento\Quote\Model\Quote;


/**
 * Class Cardinfo
 */
class Cardinfo extends Action
{
    private $_historyFactory;

    protected $_orderFactory;

    private $result;

    private $helper;

    protected $checkoutSession;  

    protected $quote;

    public function __construct(
        Context $context,
        JsonFactory    $resultJsonFactory,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        Hepsipayapi $helper,
        HistoryFactory $historyFactory,
        Quote $quote,
        \Magento\Checkout\Model\Session $checkoutSession
    ) {
        parent::__construct($context);
        $this->helper = $helper;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_orderFactory = $orderFactory;
        $this->_historyFactory = $historyFactory;
        $this->quote = $quote;        
        $this->checkoutSession = $checkoutSession;
    }
    /**
     * @inheritdoc
     */
    public function execute()
    {
        if(isset($_POST)){           
            $defaults = $_POST;           
        }
        $cc_number='';        
        if(!isset($_POST['cc_number'])){           
            return false;           
        }
        if(isset($_POST['cc_number'])){
            $cc_number = $_POST['cc_number'];
        }
        if(isset($_POST['cc_number'])){
            $installment = $_POST['installments'];
        }
        if(strlen($cc_number<16)){
            return false;
        }
        if($installment > 1){
            $this->result = $this->helper->sendSale($defaults, 'SaleInstallment');
        }else{            
            $this->result = $this->helper->sendSale($defaults, 'Sale');
        }
        
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $cart = $objectManager->get('\Magento\Checkout\Model\Cart');

        $store = $objectManager->get('Magento\Store\Model\StoreManagerInterface');
        $store_id = $store->getStore()->getId();

        $grandTotal = $cart->getQuote()->getGrandTotal();

        $getClientIp = $this->helper->getClientIp();


        $resultj = $this->resultJsonFactory->create();

        $historyModel = $this->_historyFactory->create();

        $collection = $historyModel->getCollection();        
        $logdata = array();

        /*response in html when we use 3D_Secure*/
        
        if (is_object($this->result) AND  $this->result->status AND isset($this->result->html)) {
            $this->checkoutSession->setHepsipay([
                'secure'=>true,
                'html'=>$this->result->html
            ]);
        } else if (isset($this->result) && is_object($this->result)) {
            
            foreach ($this->result as $key => $value) {

                if($key == 'total'){
                    if($this->result->original_currency == $this->result->currency){
                        $logdata['total']=$value;
                        $logdata['total_try']=$value;
                        $commission_total = $value - $grandTotal;
                        $this->checkoutSession->setHepsipay(['hepsipay_commission'=>$commission_total]);
                        $hepsipay = $this->checkoutSession->getHepsipay();
                        $logdata['commission_total'] = $commission_total;
                    }else{
                        $total = $value * $this->result->conversion_rate;
                        $logdata['total'] = round($total, 2);
                        $logdata['total_try']=$value;
                        $commission_total = $logdata['total'] - $grandTotal;
                        $this->checkoutSession->setHepsipay(['hepsipay_commission'=>$commission_total]);
                        $hepsipay = $this->checkoutSession->getHepsipay();
                        $logdata['commission_total'] = $commission_total;
                    }
                }elseif($key == 'transaction_id'){                        
                    $logdata['transaction_id']=$value;
                }elseif($key == 'total_try'){                        
                    $logdata['total_try']=$value;
                }elseif($key == 'conversion_rate'){                        
                    $logdata['conversion_rate']=$value;
                }elseif($key == 'bank_id'){                        
                    $logdata['bank_id']=$value;
                }elseif($key == 'use3d'){ 
                    if($value == 0){
                        $logdata['use3d']='No';
                    }else{
                        $logdata['use3d']='Yes';
                    }                       
                }elseif($key == 'installments'){                        
                    $logdata['installments']=$value;
                }elseif($key == 'status'){ 
                    if($value == 0){
                        $logdata['status']='Failed';
                    }else{
                        $logdata['status']='Complete';
                    }
                }elseif($key == 'time'){                        
                    $logdata['date_added']=$value;
                }
            }
            $logdata['client_ip']=$getClientIp;
            $logdata['store_id'] = $store_id;
            $this->checkoutSession->setHepsipaylog($logdata);
            return $resultj->setData($this->result);
        }
        
    }
   
}
