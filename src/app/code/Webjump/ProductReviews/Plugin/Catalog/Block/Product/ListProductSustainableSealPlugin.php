<?php

declare(strict_types=1);

namespace Webjump\ProductReviews\Plugin\Catalog\Block\Product;

use Magento\Catalog\Block\Product\ListProduct;
use Magento\Catalog\Model\Product;
use Magento\Framework\View\Element\Template;

class ListProductSustainableSealPlugin
{
    public function afterGetProductDetailsHtml(
        ListProduct $subject,
        string $result,
        Product $product
    ): string {
        if (!(bool) $product->getData('sustainable_seal')) {
            return $result;
        }

        $sealBlock = $subject->getLayout()->createBlock(Template::class);

        if (!$sealBlock) {
            return $result;
        }

        $sealBlock->setTemplate(
            'Webjump_ProductReviews::product/sustainable-seal.phtml'
        );

        $sealBlock->setData('product', $product);

        return $result . $sealBlock->toHtml();
    }
}