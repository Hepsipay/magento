<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Hepsipay\Hepsipay\Controller\Payment;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Hepsipay\Hepsipay\Helper\Hepsipayapi;
use Hepsipay\Hepsipay\Model\HistoryFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Checkout\Model\Session;
use Magento\Sales\Model\OrderFactory;
use Magento\Quote\Model\Quote;
use Magento\Checkout\Model\Type\Onepage;


/**
 * Class Return3D
 */
class Return3D extends Action
{
    protected $_orderFactory;

    protected $result;

    protected $helper;  

    protected $checkoutSession;

    protected $paymentModel;   

    protected $_historyFactory;

    protected $resultRedirect;

    protected $cartManagement;

    protected $quote;

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        OrderFactory $orderFactory,
        Hepsipayapi $helper,
        Session $checkoutSession,
        CartManagementInterface $cartManagement,
        Quote $quote,
        HistoryFactory $historyFactory
    ) {
        parent::__construct($context);
        $this->helper = $helper;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_orderFactory = $orderFactory;
        $this->checkoutSession = $checkoutSession;
        $this->_historyFactory = $historyFactory;
        $this->cartManagement = $cartManagement;
        $this->quote = $quote;
        $this->resultRedirect = $context->getResultFactory();
    }
    /**
     * @inheritdoc
     */
    public function execute()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        
        $store = $objectManager->get('Magento\Store\Model\StoreManagerInterface');
        $store_id = $store->getStore()->getId();

        $this->quote = $this->checkoutSession->getQuote();
        $this->quote->getPayment()->setMethod('hepsipay');

        $resultRedirect = $this->resultRedirect->create(ResultFactory::TYPE_REDIRECT);
        
        if(isset($_REQUEST['status'])) {
            $result = $_REQUEST;
            $resultj = $this->resultJsonFactory->create();

            $getClientIp = $this->helper->getClientIp();

            $historyModel = $this->_historyFactory->create();
            $collection = $historyModel->getCollection();        
            /*add in if last -> && $result->status === true*/

            if(isset($result)) {
                foreach ($result as $key => $value) {
                    if($key == 'total'){
                        if($result['original_currency'] == $result['currency']){
                            $logdata['total']=$value;
                            $logdata['total_try']=$value;
                            $commission_total = $value - $result['original_total'];
                            $this->checkoutSession->setHepsipay(['hepsipay_commission'=>$commission_total]);
                            // $hepsipay = $this->checkoutSession->getHepsipay();
                            $logdata['commission_total'] = $commission_total;
                        }else{
                            $total = $result['original_total'];                            
                            $installments = $this->checkoutSession->getInstallmentInfo();
                            if($installments)
                            {
                                foreach ($installments as $index => $commission) {
                                    if($result['installments'] == ++$index){
                                        $percent = filter_var($commission->commission, FILTER_SANITIZE_NUMBER_INT);
                                        $total += (($total * $percent) / 100); 
                                    }
                                }
                            }
                            $logdata['total']=$total;
                            $logdata['total_try']=$value;
                            $commission_total = $total - $result['original_total'];
                            $this->checkoutSession->setHepsipay(['hepsipay_commission'=>$commission_total]);
                            // $hepsipay = $this->checkoutSession->getHepsipay();
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
                    }elseif($key == 'extra_installments'){                        
                        $logdata['extra_installments']=$value;
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
            }

            if ( $logdata['status'] == 'Failed' ){
                $resultRedirect->setPath('checkout/onepage/failure', ['_secure' => true]);
            } else {
                $this->cartManagement->placeOrder($this->quote->getId());
                $resultRedirect->setPath('checkout/onepage/success', ['_secure' => true]);                
            }
            return $resultRedirect;
        }else{
            $resultRedirect->setPath('checkout/onepage/failure', ['_secure' => true]);
            return $resultRedirect;
        }

    }
}
