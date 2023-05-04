<?php
namespace Blizzard\Warcraft\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $table = $installer->getConnection()
            ->newTable($installer->getTable('blizzard_warcraft'))
            ->addColumn(
                'customer_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => false, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Customer ID'
            )
            ->addColumn(
                'level',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '1'],
                'Level'
            )
            ->addColumn(
                'experience',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Experience'
            )
            ->addColumn(
                'promotion',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Promotion'
            )
            ->addColumn(
                'rank',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Rank'
            )
            ->addForeignKey(
                $installer->getFkName('blizzard_warcraft', 'customer_id', 'customer_entity', 'entity_id'),
                'customer_id',
                $installer->getTable('customer_entity'),
                'entity_id',
                Table::ACTION_CASCADE
            )
            ->setComment('Blizzard Warcraft Table');

        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
