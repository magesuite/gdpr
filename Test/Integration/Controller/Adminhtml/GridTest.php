<?php

namespace MageSuite\Gdpr\Test\Integration\Controller\Adminhtml;

class GridTest extends AnonymizationTestsCommon
{
    const GRID_DATA_PROVIDER_URL = 'backend/mui/index/render/?namespace=sales_order_grid';

    /**
     * @magentoDbIsolation enabled
     * @magentoAppArea adminhtml
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Sales/_files/order.php
     */
    public function testGridDataIsAnonymyzedByDefault()
    {

        $this->dispatch(self::GRID_DATA_PROVIDER_URL);

        $gridConfiguration = $this->getGridConfiguration();

        $orders = $gridConfiguration["*"]["Magento_Ui/js/core/app"]["components"]["sales_order_grid"]["children"]["sales_order_grid_data_source"]["config"]["data"]["items"];

        $this->assertEquals('f*****************', $orders[0]['shipping_name']);
        $this->assertEquals('f*****************', $orders[0]['billing_name']);
        $this->assertEquals('s**************************', $orders[0]['shipping_address']);
        $this->assertEquals('s**************************', $orders[0]['billing_address']);
        $this->assertEquals('c****************', $orders[0]['customer_email']);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppArea adminhtml
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Sales/_files/order.php
     */
    public function testGridDataIsNotAnonymyzedWhenUserHasPermissions()
    {
        $this->acl->deny(null, \MageSuite\Gdpr\Helper\CustomerDataVisibility::HIDE_CUSTOMER_DATA_RESOURCE);

        $this->dispatch(self::GRID_DATA_PROVIDER_URL);

        $gridConfiguration = $this->getGridConfiguration();

        $orders = $gridConfiguration["*"]["Magento_Ui/js/core/app"]["components"]["sales_order_grid"]["children"]["sales_order_grid_data_source"]["config"]["data"]["items"];

        $this->assertEquals('firstname lastname', $orders[0]['shipping_name']);
        $this->assertEquals('firstname lastname', $orders[0]['billing_name']);
        $this->assertContains('Los Angeles', $orders[0]['shipping_address']);
        $this->assertContains('Los Angeles', $orders[0]['billing_address']);
        $this->assertEquals('customer@null.com', $orders[0]['customer_email']);
    }
}
