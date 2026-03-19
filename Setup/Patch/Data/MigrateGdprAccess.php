<?php

declare(strict_types=1);

namespace MageSuite\Gdpr\Setup\Patch\Data;

class MigrateGdprAccess implements \Magento\Framework\Setup\Patch\DataPatchInterface
{
    public function __construct(
        protected \Magento\Authorization\Model\Acl\AclRetriever $aclRetriever,
        protected \Magento\User\Model\ResourceModel\User\Collection $userCollection,
        protected \Magento\Framework\App\ResourceConnection $resourceConnection
    ) {}

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }

    public function apply(): void
    {
        $connection = $this->resourceConnection->getConnection();

        $select = $connection->select()
            ->from('authorization_rule')
            ->where('resource_id = ?', 'MageSuite_Gdpr::gdpr');

        $rules = $connection->fetchAll($select);

        foreach ($rules as $rule) {
            $hideGdprAccess = $rule['permission'] === 'allow';
            
            $connection->update(
                'authorization_role',
                ['gdpr_access' => $hideGdprAccess ? 0 : 1],
                ['role_id = ?' => $rule['role_id']]
            );

            $connection->insert(
                'authorization_rule',
                [
                    'role_id' => $rule['role_id'],
                    'resource_id' => 'MageSuite_Gdpr::gdpr_config',
                    'permission' => $hideGdprAccess ? 'deny' : 'allow',
                ]
            );
        }

        $connection->delete(
            'authorization_rule',
            ['resource_id = ?' => 'MageSuite_Gdpr::hide_customer_data']
        );

        $connection->delete(
            'authorization_rule',
            ['resource_id = ?' => 'MageSuite_Gdpr::gdpr']
        );
    }
}
