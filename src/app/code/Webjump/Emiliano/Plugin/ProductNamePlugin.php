<?php

declare(strict_types=1);

namespace Webjump\Emiliano\Plugin;

use Magento\Catalog\Model\Product;

class ProductNamePlugin
{
    private const SUFFIX = ' [Oak & Barrel]';

    public function afterGetName(
        Product $subject,
        ?string $result
    ): ?string {
        if ($result === null || $result === '') {
            return $result;
        }

        if (str_ends_with($result, self::SUFFIX)) {
            return $result;
        }

        return $result . self::SUFFIX;
    }
}