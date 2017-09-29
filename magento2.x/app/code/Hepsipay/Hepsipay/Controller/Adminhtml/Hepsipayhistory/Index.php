<?php
namespace Hepsipay\Hepsipay\Controller\Adminhtml\Hepsipayhistory;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Index action
     *
     * @return void
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Hepsipay_Hepsipay::history');
        $resultPage->addBreadcrumb(__('Hepsipay'), __('Hepsipay'));
        $resultPage->addBreadcrumb(__('Hepsipay History'), __('Hepsipay History'));
        $resultPage->getConfig()->getTitle()->prepend(__('Hepsipay History'));

        return $resultPage;
    }
}
