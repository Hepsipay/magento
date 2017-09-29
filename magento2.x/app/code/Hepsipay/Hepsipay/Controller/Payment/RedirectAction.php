<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Hepsipay\Hepsipay\Controller\Payment;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Hepsipay\Hepsipay\Helper\Hepsipayapi;
/**
 * Class RedirectAction
 */
class RedirectAction extends Action
{
     
    private $result;

    private $helper;  

    protected $checkoutSession;   

    public function __construct(
        Context $context,
        JsonFactory    $resultJsonFactory,
        Hepsipayapi $helper,
        \Magento\Checkout\Model\Session $checkoutSession
    ) {
        parent::__construct($context);
        $this->helper = $helper;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->checkoutSession = $checkoutSession;
    }
    /**
     * @inheritdoc
     */
    public function execute()
    {
        $hepsipay = $this->checkoutSession->getHepsipay();
        if(isset($hepsipay['html'])){
            $html = $hepsipay['html'];
            unset($hepsipay['html']);
            echo $html;
            exit;    
        }
        
    }
   
}
