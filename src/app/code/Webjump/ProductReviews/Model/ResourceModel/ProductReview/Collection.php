<?php

declare(strict_types=1);

namespace Webjump\ProductReviews\Model\ResourceModel\ProductReview;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Webjump\ProductReviews\Model\ProductReview as ProductReviewModel;
use Webjump\ProductReviews\Model\ResourceModel\ProductReview as ProductReviewResource;

class Collection extends AbstractCollection
{
    protected function _construct(): void
    {
        $this->_init(
            ProductReviewModel::class,
            ProductReviewResource::class
        );
    }
}