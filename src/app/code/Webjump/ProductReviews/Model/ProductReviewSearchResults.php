<?php

declare(strict_types=1);

namespace Webjump\ProductReviews\Model;

use Magento\Framework\Api\SearchResults;
use Webjump\ProductReviews\Api\Data\ProductReviewSearchResultsInterface;

class ProductReviewSearchResults extends SearchResults implements ProductReviewSearchResultsInterface
{
    public function getItems(): array
    {
        return parent::getItems();
    }

    public function setItems(array $items): self
    {
        parent::setItems($items);

        return $this;
    }
}