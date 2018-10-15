<?php

namespace MageSuite\Gdpr\Test\Integration\Controller\Adminhtml;

class OrderViewTest extends AnonymizationTestsCommon
{
    const ORDER_VIEW_URL = 'backend/sales/order/view/order_id/%s';

    protected $addressData = [
        'firstname' => 'firstname',
        'lastname' => 'lastname',
        'street' => 'street',
        'city' => 'Los Angeles',
        'phone' => '11111111',
        'country' => 'United States',
    ];

    /**
     * @magentoDbIsolation enabled
     * @magentoAppArea adminhtml
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Sales/_files/order.php
     * @magentoDataFixture addShippingMethodToOrder
     */
    public function testOrderDataIsAnonymyzedByDefault()
    {
        $body = $this->getOrderViewHtml();

        $orderInformationHtml = $this->getElementHtml($body, "//*[contains(@class, 'order-account-information')]");

        $this->assertNotContains('customer@null.com', $orderInformationHtml);

        $orderShippingAddressHtml = $this->getElementHtml($body, "//*[contains(@class, 'order-billing-address')]");
        $orderBillingAddressHtml = $this->getElementHtml($body, "//*[contains(@class, 'order-shipping-address')]");
        $this->assertAddress('notContains', $orderShippingAddressHtml, $this->addressData);
        $this->assertAddress('notContains', $orderBillingAddressHtml, $this->addressData);

        $paymentInformation = $this->getElementHtml($body, "//*[contains(@class, 'order-payment-method-title')]");

        $this->assertContains('Payment details are not shown. It may contain personal data.', $paymentInformation);
    }

    protected function assertAddress($type, $html, $addressData)
    {
        $assertionMethod = $type == 'notContains' ? 'assertNotContains' : 'assertContains';

        $this->$assertionMethod($addressData['firstname'], $html);
        $this->$assertionMethod($addressData['lastname'], $html);
        $this->$assertionMethod($addressData['street'], $html);
        $this->$assertionMethod($addressData['city'], $html);
        $this->$assertionMethod($addressData['phone'], $html);
        $this->$assertionMethod($addressData['country'], $html);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppArea adminhtml
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Sales/_files/order.php
     * @magentoDataFixture addShippingMethodToOrder
     */
    public function testOrderDataIsNotAnonymyzedWhenUserHasPermissions()
    {
        $this->acl->deny(null, \MageSuite\Gdpr\Helper\CustomerDataVisibility::HIDE_CUSTOMER_DATA_RESOURCE);

        $body = $this->getOrderViewHtml();

        $orderInformationHtml = $this->getElementHtml($body, "//*[contains(@class, 'order-account-information')]");

        $this->assertContains('customer@null.com', $orderInformationHtml);

        $orderShippingAddressHtml = $this->getElementHtml($body, "//*[contains(@class, 'order-billing-address')]");
        $orderBillingAddressHtml = $this->getElementHtml($body, "//*[contains(@class, 'order-shipping-address')]");

        $this->assertAddress('contains', $orderShippingAddressHtml, $this->addressData);
        $this->assertAddress('contains', $orderBillingAddressHtml, $this->addressData);

        $paymentInformation = $this->getElementHtml($body, "//*[contains(@class, 'order-payment-method-title')]");

        $this->assertNotContains('Payment details are not shown. It may contain personal data.', $paymentInformation);
    }

    protected function getElementHtml($html, $selector)
    {
        $domDocument = new \DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $domDocument->loadHTML($html);
        libxml_use_internal_errors(false);
        $domXpath = new \DOMXPath($domDocument);

        $element = $domXpath->query($selector)->item(0);

        $newdoc = new \DOMDocument();
        $cloned = $element->cloneNode(TRUE);
        $newdoc->appendChild($newdoc->importNode($cloned, TRUE));

        return $newdoc->saveHTML();
    }

    /**
     * @return mixed
     */
    public static function getOrder()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $order = $objectManager->create(\Magento\Sales\Model\Order::class);
        return $order->loadByIncrementId('100000001');
    }

    /**
     * @return mixed
     */
    protected function getOrderViewHtml()
    {
        $order = self::getOrder();

        $url = sprintf(self::ORDER_VIEW_URL, $order->getId());

        $this->dispatch($url);

        return $this->getResponse()->getBody();
    }

    public static function addShippingMethodToOrder()
    {
        $order = self::getOrder();
        $order->setShippingMethod('flatrate_flatrate');
        $order->save();
    }
}
