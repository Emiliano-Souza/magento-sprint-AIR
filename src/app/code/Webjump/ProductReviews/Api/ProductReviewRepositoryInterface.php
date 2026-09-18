<?php

declare(strict_types=1);

namespace Webjump\ProductReviews\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Webjump\ProductReviews\Api\Data\ProductReviewInterface;
use Webjump\ProductReviews\Api\Data\ProductReviewSearchResultsInterface;

interface ProductReviewRepositoryInterface
{
    public function save(
        ProductReviewInterface $productReview
    ): ProductReviewInterface;

    public function getById(
        int $reviewId
    ): ProductReviewInterface;

    public function delete(
        ProductReviewInterface $productReview
    ): bool;

    public function getList(
        SearchCriteriaInterface $searchCriteria
    ): ProductReviewSearchResultsInterface;
}