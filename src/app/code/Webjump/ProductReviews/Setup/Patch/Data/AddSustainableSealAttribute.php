<?php

declare(strict_types=1);

namespace Webjump\ProductReviews\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\Source\Boolean;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddSustainableSealAttribute implements DataPatchInterface
{
    public function __construct(
        private readonly ModuleDataSetupInterface $moduleDataSetup,
        private readonly EavSetupFactory $eavSetupFactory
    ) {
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $eavSetup = $this->eavSetupFactory->create([
            'setup' => $this->moduleDataSetup,
        ]);

        $eavSetup->addAttribute(
            Product::ENTITY,
            'sustainable_seal',
            [
                'type' => 'int',
                'label' => 'Selo Sustentável',
                'input' => 'boolean',
                'source' => Boolean::class,
                'required' => false,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'group' => 'Product Details',
                'sort_order' => 200,
                'visible' => true,
                'user_defined' => true,
                'default' => 0,
                'used_in_product_listing' => false,
                'visible_on_front' => false,
            ]
        );

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }
}