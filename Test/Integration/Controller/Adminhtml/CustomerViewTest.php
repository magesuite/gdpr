<?php

declare(strict_types=1);

namespace MageSuite\Gdpr\Test\Integration\Controller\Adminhtml;

class CustomerViewTest extends AnonymizationTestsCommon
{
    public const CUSTOMER_VIEW_URL = 'backend/customer/index/edit/id/%s/';

    protected ?\Magento\Customer\Model\Customer $customer = null;

    public function setUp(): void
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
    public function testCustomerViewWithAccessDenied(): void
    {
        $this->denyAccess();

        $url = sprintf(self::CUSTOMER_VIEW_URL, $this->getCustomerId());

        $this->dispatch($url);
        $html = $this->getResponse()->getBody();

        $assertContains = method_exists($this, 'assertStringContainsString') ? 'assertStringContainsString' : 'assertContains';

        $this->$assertContains('You don\'t have permission to view this page.', $html);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppArea adminhtml
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Customer/_files/customer.php
     */
    public function testCustomerViewWithAccessGranted(): void
    {
        $this->grantAccess();

        $url = sprintf(self::CUSTOMER_VIEW_URL, $this->getCustomerId());

        $this->dispatch($url);
        $html = $this->getResponse()->getBody();

        $assertContains = method_exists($this, 'assertStringContainsString') ? 'assertStringContainsString' : 'assertContains';

        $this->$assertContains('John Smith', $html);
    }

    protected function getCustomerId(): int
    {
        $this->customer->setWebsiteId(1);
        $this->customer->loadByEmail('customer@example.com');

        return (int)$this->customer->getId();
    }
}
