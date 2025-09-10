<?php

declare(strict_types=1);

namespace MageSuite\Gdpr\Test\Integration\Controller\Adminhtml;

class AnonymizationTestsCommon extends \Magento\TestFramework\TestCase\AbstractBackendController
{
    protected ?\Magento\Framework\App\ObjectManager $objectManager = null;
    protected ?\Magento\Framework\Acl\Builder $acl = null;

    public function setUp(): void
    {
        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->acl = $this->objectManager->get(\Magento\Framework\Acl\Builder::class);

        parent::setUp();
    }

    protected function getGridConfiguration(string $html): array
    {
        $domDocument = $this->prepareDomDocument($html);

        $tag = 'script';
        $content = $domDocument->getElementsByTagname($tag);

        return json_decode($content->item(0)->nodeValue, true);
    }

    protected function prepareDomDocument(string $html): \DOMDocument
    {
        $domDocument = new \DOMDocument('1.0', 'UTF-8');

        libxml_use_internal_errors(true);
        $domDocument->loadHTML($html);
        libxml_use_internal_errors(false);

        return $domDocument;
    }
}
