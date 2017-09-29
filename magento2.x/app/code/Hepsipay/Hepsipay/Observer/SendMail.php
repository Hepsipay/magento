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
use Magento\Sales\Model\Order\Email\Sender\OrderSender;

class SendMail implements \Magento\Framework\Event\ObserverInterface {
    /**
     * @var \Magento\Framework\ObjectManager\ObjectManager
    */
    protected $_objectManager;    
    protected $_orderFactory;    
    protected $_checkoutSession;
    protected $_historyFactory;
    protected $logger;
    protected $helper;
    protected $orderSender;
    
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        HistoryFactory $historyFactory,
        Hepsipayapi $helper,
        \Psr\Log\LoggerInterface $logger,
        OrderSender $orderSender,
        \Magento\Framework\ObjectManager\ObjectManager $objectManager
    ) {        
        $this->_objectManager = $objectManager;        
        $this->_orderFactory = $orderFactory;
        $this->_checkoutSession = $checkoutSession;    
        $this->_historyFactory = $historyFactory;
        $this->logger = $logger;
        $this->orderSender = $orderSender;
        $this->helper = $helper;
    }
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $orderIds = $observer->getEvent()->getOrderIds(); 
        if (count($orderIds)) {
            $orderId = $orderIds[0];            
            $order = $this->_orderFactory->create()->load($orderId); 

            $hepsipaydata = $this->_checkoutSession->getHepsipaylog();
            if($hepsipaydata['use3d'] == 'Yes' || ($hepsipaydata['bank_id'] == 'BKMExpress' && $hepsipaydata['mail_send'] != '1') ){
                try {
                    $this->orderSender->send($order);
                } catch (\Exception $e) {
                    $this->logger->critical($e);
                }
            }
        }
        if($this->_checkoutSession){
            unset($this->_checkoutSession);
        }
    }
}
