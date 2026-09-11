<?php

declare(strict_types=1);

namespace Webjump\Emiliano\Plugin;

use Magento\Catalog\Model\Product;

class ProductNamePlugin
{
    public function afterGetName(Product $subject, string $result): string
    {
        return $result . ' [Oak & Barrel]';
    }
}