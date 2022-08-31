<?php

namespace MageSuite\Gdpr\Test\Integration\Controller\Adminhtml;

class OrderGridTest extends AnonymizationTestsCommon
{
    const GRID_DATA_PROVIDER_URL = 'backend/mui/index/render/?namespace=sales_order_grid';

    /**
     * @magentoDbIsolation enabled
     * @magentoAppArea adminhtml
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Sales/_files/order.php
     */
    public function testOrderGridDataIsAnonymyzedByDefault()
    {

        $this->dispatch(self::GRID_DATA_PROVIDER_URL);
        $html = $this->getResponse()->getBody();

        $gridConfiguration = $this->getGridConfiguration($html);

        $productMetadata = $this->objectManager->get(\Magento\Framework\App\ProductMetadataInterface::class);
        $version = $productMetadata->getVersion();

        $orders = $gridConfiguration["*"]["Magento_Ui/js/core/app"]["components"]["sales_order_grid"]["children"]["sales_order_grid_data_source"]["config"]["data"]["items"];

        $this->assertEquals('f*****************', $orders[0]['shipping_name']);
        $this->assertEquals('f*****************', $orders[0]['billing_name']);

        // from Magento 2.4.4
        // dev/tests/integration/testsuite/Magento/Sales/_files/address_data.php fixture
        // contains company name that affects shipping and billing address output
        if (version_compare($version, '2.4.4', '>=')) {
            $this->assertEquals('T***************************************', $orders[0]['shipping_address']);
            $this->assertEquals('T***************************************', $orders[0]['billing_address']);
        } else {
            $this->assertEquals('s**************************', $orders[0]['shipping_address']);
            $this->assertEquals('s**************************', $orders[0]['billing_address']);
        }

        if (version_compare($version, '2.4.5', '>=')) {
            $this->assertEquals('c*******************', $orders[0]['customer_email']);
        } else {
            $this->assertEquals('c****************', $orders[0]['customer_email']);
        }
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppArea adminhtml
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Sales/_files/order.php
     */
    public function testOrderGridDataIsNotAnonymyzedWhenUserHasPermissions()
    {
        $productMetadata = $this->objectManager->get(\Magento\Framework\App\ProductMetadataInterface::class);
        $version = $productMetadata->getVersion();

        $this->acl->deny(null, \MageSuite\Gdpr\Helper\CustomerDataVisibility::HIDE_CUSTOMER_DATA_RESOURCE);

        $this->dispatch(self::GRID_DATA_PROVIDER_URL);
        $html = $this->getResponse()->getBody();

        $gridConfiguration = $this->getGridConfiguration($html);

        $orders = $gridConfiguration["*"]["Magento_Ui/js/core/app"]["components"]["sales_order_grid"]["children"]["sales_order_grid_data_source"]["config"]["data"]["items"];

        $assertContains = method_exists($this, 'assertStringContainsString') ? 'assertStringContainsString' : 'assertContains';

        $this->assertEquals('firstname lastname', $orders[0]['shipping_name']);
        $this->assertEquals('firstname lastname', $orders[0]['billing_name']);
        $this->$assertContains('Los Angeles', $orders[0]['shipping_address']);
        $this->$assertContains('Los Angeles', $orders[0]['billing_address']);

        if (version_compare($version, '2.4.5', '>=')) {
            $this->assertEquals('customer@example.com', $orders[0]['customer_email']);
        } else {
            $this->assertEquals('customer@null.com', $orders[0]['customer_email']);
        }
    }
}
