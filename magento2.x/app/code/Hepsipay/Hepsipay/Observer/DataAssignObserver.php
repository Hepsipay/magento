<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Hepsipay\Hepsipay\Observer;

use Magento\Framework\Event\Observer;
use Magento\Payment\Observer\AbstractDataAssignObserver;
use Magento\Framework\ObjectManager\ObjectManager;
use Hepsipay\Hepsipay\Model\HistoryFactory;
use Hepsipay\Hepsipay\Helper\Hepsipayapi;

class DataAssignObserver implements \Magento\Framework\Event\ObserverInterface {
    /**
     * @var \Magento\Framework\ObjectManager\ObjectManager
    */
    protected $_objectManager;
    
    protected $_orderFactory;    
    protected $_checkoutSession;
    private $_historyFactory;
    protected $logger;
    private $helper;
    
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        HistoryFactory $historyFactory,
        Hepsipayapi $helper,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\ObjectManager\ObjectManager $objectManager
    ) {        
        $this->_objectManager = $objectManager;        
        $this->_orderFactory = $orderFactory;
        $this->_checkoutSession = $checkoutSession;    
        $this->_historyFactory = $historyFactory;
        $this->logger = $logger;
        $this->helper = $helper;
    }
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        if($observer->getEvent()->getOrder()->getPayment()->getMethodInstance()->getCode() == 'hepsipay'){
            $order = $observer->getEvent()->getOrder(); 

            $hepsipay = $this->_checkoutSession->getHepsipay();
            if(isset($hepsipay['hepsipay_commission'])){
                $commission = $hepsipay['hepsipay_commission'];
                unset($hepsipay['hepsipay_commission']);
                $order->setHepsipayCommission($commission);
            }
            
            $hepsipaydata = $this->_checkoutSession->getHepsipaylog();

            if($hepsipaydata['status'] == 'Complete'){
                $order->setStatus('complete');
            } else {
                $order->setStatus('canceled');
            }
            
            $historyModel = $this->_historyFactory->create();
           
            if(isset($hepsipaydata['transaction_id'])){
                $orderIncrementId = $order->getIncrementId();
                $hepsipaydata['order_id'] = $orderIncrementId;
                $historyModel->setData($hepsipaydata);
                $historyModel->save();
            }
            unset($hepsipaydata['status']);
        }
    }
}
