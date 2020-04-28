<?php

namespace MageSuite\Gdpr\Test\Integration\Controller\Adminhtml;

class CustomerViewTest extends AnonymizationTestsCommon
{
    const CUSTOMER_VIEW_URL = 'backend/customer/index/edit/id/%s/';

    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $customer;

    public function setUp()
    {
        parent::setUp();

        $this->customer = $this->objectManager->create(\Magento\Customer\Model\Customer::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppArea adminhtml
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Customer/_files/customer.php
     */
    public function testAccessDeniedPage()
    {
        $url = sprintf(self::CUSTOMER_VIEW_URL, $this->getCustomerId());

        $this->dispatch($url);
        $html = $this->getResponse()->getBody();

        $this->assertContains('You don\'t have permision to view this page.', $html);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppArea adminhtml
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Customer/_files/customer.php
     */
    public function testCustomerPageWhileUserHavePermissions()
    {
        $this->acl->deny(null, \MageSuite\Gdpr\Helper\CustomerDataVisibility::HIDE_CUSTOMER_DATA_RESOURCE);

        $url = sprintf(self::CUSTOMER_VIEW_URL, $this->getCustomerId());

        $this->dispatch($url);
        $html = $this->getResponse()->getBody();

        $this->assertContains('John Smith', $html);
    }

    protected function getCustomerId()
    {
        $this->customer->setWebsiteId(1);
        $this->customer->loadByEmail('customer@example.com');

        return $this->customer->getId();
    }
}
