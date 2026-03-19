<?php

declare(strict_types=1);

namespace MageSuite\Gdpr\Test\Integration\Controller\Adminhtml;

class OrderViewTest extends AnonymizationTestsCommon
{
    public const ORDER_VIEW_URL = 'backend/sales/order/view/order_id/%s';

    protected ?\Magento\Sales\Model\Order $order = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->order = $this->objectManager->create(\Magento\Sales\Model\Order::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppArea adminhtml
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Sales/_files/order.php
     */
    public function testOrderViewWithAccessDenied(): void
    {
        $this->denyAccess();

        $html = $this->getOrderViewHtml();

        $assertContains = method_exists($this, 'assertStringContainsString') ? 'assertStringContainsString' : 'assertContains';
        $assertNotContains = method_exists($this, 'assertStringNotContainsString') ? 'assertStringNotContainsString' : 'assertNotContains';

        $productMetadata = $this->objectManager->get(\Magento\Framework\App\ProductMetadataInterface::class);
        $version = $productMetadata->getVersion();

        if (version_compare($version, '2.4.5', '>=')) {
            $this->$assertNotContains(
                'customer@example.com',
                $this->getElementHtml($html, "//*[contains(@class, 'order-account-information')]")
            );
        } else {
            $this->$assertNotContains(
                'customer@null.com',
                $this->getElementHtml($html, "//*[contains(@class, 'order-account-information')]")
            );
        }

        $this->$assertContains(
            'Payment details are not shown. It may contain personal data.',
            $this->getElementHtml($html, "//*[contains(@class, 'order-payment-method-title')]")
        );

        $this->assertAddress(
            'assertNotContains',
            $this->getElementHtml($html, "//*[contains(@class, 'order-shipping-address')]")
        );

        $this->assertAddress(
            'assertNotContains',
            $this->getElementHtml($html, "//*[contains(@class, 'order-billing-address')]")
        );
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppArea adminhtml
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Sales/_files/order.php
     */
    public function testOrderViewWithAccessGranted(): void
    {
        $this->grantAccess();

        $productMetadata = $this->objectManager->get(\Magento\Framework\App\ProductMetadataInterface::class);
        $version = $productMetadata->getVersion();

        $html = $this->getOrderViewHtml();

        $assertContains = method_exists($this, 'assertStringContainsString') ? 'assertStringContainsString' : 'assertContains';
        $assertNotContains = method_exists($this, 'assertStringNotContainsString') ? 'assertStringNotContainsString' : 'assertNotContains';

        if (version_compare($version, '2.4.5', '>=')) {
            $this->$assertContains(
                'customer@example.com',
                $this->getElementHtml($html, "//*[contains(@class, 'order-account-information')]")
            );
        } else {
            $this->$assertContains(
                'customer@null.com',
                $this->getElementHtml($html, "//*[contains(@class, 'order-account-information')]")
            );
        }

        $this->$assertNotContains(
            'Payment details are not shown. It may contain personal data.',
            $this->getElementHtml($html, "//*[contains(@class, 'order-payment-method-title')]")
        );

        $this->assertAddress(
            'assertContains',
            $this->getElementHtml($html, "//*[contains(@class, 'order-shipping-address')]")
        );

        $this->assertAddress(
            'assertContains',
            $this->getElementHtml($html, "//*[contains(@class, 'order-billing-address')]")
        );
    }

    protected function getElementHtml(string $html, string $selector): string
    {
        $domDocument = new \DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $domDocument->loadHTML($html);
        libxml_use_internal_errors(false);
        $domXpath = new \DOMXPath($domDocument);

        $element = $domXpath->query($selector)->item(0);

        $newdoc = new \DOMDocument();
        $cloned = $element->cloneNode(true);
        $newdoc->appendChild($newdoc->importNode($cloned, true));

        return $newdoc->saveHTML();
    }

    protected function getOrderViewHtml(): string
    {
        $order = $this->order->loadByIncrementId('100000001');

        $url = sprintf(self::ORDER_VIEW_URL, $order->getId());
        $this->dispatch($url);

        return $this->getResponse()->getBody();
    }

    protected function assertAddress(string $assertionMethod, string $html): void
    {
        $addressData = $this->getAddressData();

        $assertionMethod = match ($assertionMethod) {
            'assertContains' => (method_exists($this, 'assertStringContainsString') ? 'assertStringContainsString' : 'assertContains'),
            'assertNotContains' => (method_exists($this, 'assertStringNotContainsString') ? 'assertStringNotContainsString' : 'assertNotContains'),
            default => $assertionMethod,
        };

        $this->$assertionMethod($addressData['firstname'], $html);
        $this->$assertionMethod($addressData['lastname'], $html);
        $this->$assertionMethod($addressData['street'], $html);
        $this->$assertionMethod($addressData['city'], $html);
        $this->$assertionMethod($addressData['phone'], $html);
        $this->$assertionMethod($addressData['country'], $html);
    }

    private function getAddressData(): array
    {
        return [
            'firstname' => 'firstname',
            'lastname' => 'lastname',
            'street' => 'street',
            'city' => 'Los Angeles',
            'phone' => '11111111',
            'country' => 'United States',
        ];
    }
}
