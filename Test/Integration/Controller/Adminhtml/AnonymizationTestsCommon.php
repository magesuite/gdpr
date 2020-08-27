<?php

namespace MageSuite\Gdpr\Test\Integration\Controller\Adminhtml;

class AnonymizationTestsCommon extends \Magento\TestFramework\TestCase\AbstractBackendController
{
    /**
     * @var \Magento\Framework\App\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Magento\Framework\Acl
     */
    protected $acl;

    public function setUp(): void
    {
        parent::setUp();

        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->acl = $this->objectManager->get(\Magento\Framework\Acl\Builder::class)->getAcl();
    }

    protected function getGridConfiguration($html)
    {
        $domDocument = $this->prepareDomDocument($html);

        $tag = 'script';
        $content = $domDocument->getElementsByTagname($tag);

        return json_decode($content->item(0)->nodeValue, true);
    }

    protected function prepareDomDocument($html)
    {
        $domDocument = new \DOMDocument('1.0', 'UTF-8');

        libxml_use_internal_errors(true);
        $domDocument->loadHTML($html);
        libxml_use_internal_errors(false);

        return $domDocument;
    }
}
