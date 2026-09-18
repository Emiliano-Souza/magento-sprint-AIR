<?php

declare(strict_types=1);

namespace Webjump\ProductReviews\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface ProductReviewSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return ProductReviewInterface[]
     */
    public function getItems(): array;

    /**
     * @param ProductReviewInterface[] $items
     */
    public function setItems(array $items): self;
}