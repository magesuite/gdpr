<?php

namespace MageSuite\Gdpr\Plugin\Adminhtml\Order;

class AccessDeniedPageOnAddressEdit
{
    /**
     * @var \MageSuite\Gdpr\Helper\CustomerDataVisibility
     */
    protected $customerDataVisibilityHelper;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;


    public function __construct(
        \MageSuite\Gdpr\Helper\CustomerDataVisibility $customerDataVisibilityHelper,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    )
    {
        $this->customerDataVisibilityHelper = $customerDataVisibilityHelper;
        $this->resultPageFactory = $pageFactory;
    }

    public function aroundExecute(\Magento\Sales\Controller\Adminhtml\Order\Address $subject, callable $proceed)
    {
        if (!$this->customerDataVisibilityHelper->canSeeCustomerData() && $this->customerDataVisibilityHelper->shouldDataBeAnonymized()) {
            $resultPage = $this->resultPageFactory->create();

            $resultPage->getLayout()->unsetElement('page.actions.toolbar');
            $resultPage->getConfig()->setPageLayout('1column');
            $block = $resultPage->getLayout()->createBlock('Magento\Framework\View\Element\Text', 'text_block');
            $block->setText(__('<h1>Access Denied</h1>You don\'t have permision to view this page.'));
            $resultPage->getLayout()->setBlock('sales_order_address.form.container', $block);

            return $resultPage;
        }
        return $proceed();
    }

}