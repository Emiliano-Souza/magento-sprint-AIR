<?php

declare(strict_types=1);

namespace Webjump\ProductReviews\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class UpdateSustainableSealForProductListing implements DataPatchInterface
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

        $eavSetup->updateAttribute(
            Product::ENTITY,
            'sustainable_seal',
            [
                'used_in_product_listing' => true,
            ]
        );

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public static function getDependencies(): array
    {
        return [
            AddSustainableSealAttribute::class,
        ];
    }

    public function getAliases(): array
    {
        return [];
    }
}