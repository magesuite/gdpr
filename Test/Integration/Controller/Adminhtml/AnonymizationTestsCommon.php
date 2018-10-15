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

    public function setUp()
    {
        parent::setUp();

        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->acl = $this->objectManager
            ->get(\Magento\Framework\Acl\Builder::class)
            ->getAcl();
    }

    protected function getGridConfiguration()
    {
        $html = $this->getResponse()->getBody();

        $pattern = '
        /
        \{              # { character
            (?:         # non-capturing group
                [^{}]   # anything that is not a { or }
                |       # OR
                (?R)    # recurses the entire pattern
            )*          # previous group zero or more times
        \}              # } character
        /x
        ';

        preg_match_all($pattern, $html, $matches);

        return json_decode($matches[0][0], true);
    }
}