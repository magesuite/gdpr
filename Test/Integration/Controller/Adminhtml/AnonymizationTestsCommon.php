<?php

declare(strict_types=1);

namespace MageSuite\Gdpr\Test\Integration\Controller\Adminhtml;

class AnonymizationTestsCommon extends \Magento\TestFramework\TestCase\AbstractBackendController
{
    protected ?\Magento\Framework\App\ObjectManager $objectManager = null;
    protected ?\Magento\Framework\Acl\Builder $acl = null;
    protected ?\Magento\Framework\DB\Adapter\AdapterInterface $connection = null;

    public function setUp(): void
    {
        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->acl = $this->objectManager->get(\Magento\Framework\Acl\Builder::class);
        $this->connection = $this->objectManager->get(\Magento\Framework\App\ResourceConnection::class)->getConnection();

        parent::setUp();
    }

    protected function grantAccess(): void
    {
        $this->connection->update(
            'authorization_role',
            [\MageSuite\Gdpr\Block\Role\Tab\Info::GDPR_FIELD => 1],
            ['role_id = ?' => $this->_session->getUser()->getAclRole()]
        );

        $this->_session->getUser()->getRole()->setData(\MageSuite\Gdpr\Block\Role\Tab\Info::GDPR_FIELD, 1);
    }

    protected function denyAccess(): void
    {
        $this->connection->update(
            'authorization_role',
            [\MageSuite\Gdpr\Block\Role\Tab\Info::GDPR_FIELD => 0],
            ['role_id = ?' => $this->_session->getUser()->getAclRole()]
        );

        $this->_session->getUser()->getRole()->setData(\MageSuite\Gdpr\Block\Role\Tab\Info::GDPR_FIELD, 0);
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
