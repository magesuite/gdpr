<?php

declare(strict_types=1);

namespace MageSuite\Gdpr\Plugin\Magento\Sales\Controller\Adminhtml\Order\Address;

class AccessDeniedPageOnAddressEdit
{
    public function __construct(
        protected \MageSuite\Gdpr\Helper\CustomerDataVisibility $customerDataVisibilityHelper,
        protected \Magento\Framework\View\Result\PageFactory $pageFactory
    ) {}

    public function aroundExecute(\Magento\Sales\Controller\Adminhtml\Order\Address $subject, callable $proceed)
    {
        if (!$this->customerDataVisibilityHelper->canSeeCustomerData() && $this->customerDataVisibilityHelper->shouldDataBeAnonymized()) {
            $resultPage = $this->pageFactory->create();

            $resultPage->getLayout()->unsetElement('page.actions.toolbar');
            $resultPage->getConfig()->setPageLayout('1column');

            $block = $resultPage->getLayout()->createBlock(\Magento\Framework\View\Element\Text::class, 'text_block');
            $block->setText(__('<h1>Access Denied</h1>You don\'t have permission to view this page.'));
            $resultPage->getLayout()->setBlock('sales_order_address.form.container', $block);

            return $resultPage;
        }
        return $proceed();
    }

}
