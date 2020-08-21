<?php

namespace MageSuite\Gdpr\Test\Integration\Controller\Adminhtml;

class CustomerGridTest extends AnonymizationTestsCommon
{
    const CUSTOMER_GRID_URL = 'backend/mui/index/render/?namespace=customer_listing';

    public function setUp(): void
    {
        parent::setUp();

        $indexerRegistry = $this->objectManager->create(\Magento\Framework\Indexer\IndexerRegistry::class);
        $indexer = $indexerRegistry->get(\Magento\Customer\Model\Customer::CUSTOMER_GRID_INDEXER_ID);
        $indexer->reindexAll();
    }

    /**
     * @magentoDbIsolation disabled
     * @magentoAppArea adminhtml
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Customer/_files/customer.php
     */
    public function testCustomerSectionIsAnonymyzedByDefault()
    {
        $this->dispatch(self::CUSTOMER_GRID_URL);
        $html = $this->getResponse()->getBody();

        $gridConfiguration = $this->getGridConfiguration($html);

        $customers = $gridConfiguration["*"]["Magento_Ui/js/core/app"]["components"]["customer_listing"]["children"]["customer_listing_data_source"]["config"]["data"]["items"];

        $this->assertEquals('M********************', $customers[0]['name']);
        $this->assertEquals('c*******************', $customers[0]['email']);
    }

    /**
     * @magentoDbIsolation disabled
     * @magentoAppArea adminhtml
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Customer/_files/customer.php
     */
    public function testCustomerSectionIsNotAnonymyzedhenUserHasPermissions()
    {
        $this->acl->deny(null, \MageSuite\Gdpr\Helper\CustomerDataVisibility::HIDE_CUSTOMER_DATA_RESOURCE);

        $this->dispatch(self::CUSTOMER_GRID_URL);
        $html = $this->getResponse()->getBody();

        $gridConfiguration = $this->getGridConfiguration($html);

        $customers = $gridConfiguration["*"]["Magento_Ui/js/core/app"]["components"]["customer_listing"]["children"]["customer_listing_data_source"]["config"]["data"]["items"];

        $this->assertEquals('Mr. John A Smith Esq.', $customers[0]['name']);
        $this->assertEquals('customer@example.com', $customers[0]['email']);
    }
}
