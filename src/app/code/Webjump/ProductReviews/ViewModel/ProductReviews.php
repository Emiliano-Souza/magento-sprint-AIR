<?php

declare(strict_types=1);

namespace Webjump\ProductReviews\ViewModel;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Webjump\ProductReviews\Api\Data\ProductReviewInterface;
use Webjump\ProductReviews\Api\ProductReviewRepositoryInterface;
use Webjump\ProductReviews\Model\Config;

class ProductReviews implements ArgumentInterface
{
    public function __construct(
        private readonly Config $config,
        private readonly ProductReviewRepositoryInterface $productReviewRepository,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly SortOrderBuilder $sortOrderBuilder
    ) {
    }

    public function isEnabled(): bool
    {
        return $this->config->isEnabled();
    }

    /**
     * @return ProductReviewInterface[]
     */
    public function getReviews(int $productId): array
    {
        if (!$this->isEnabled() || $productId <= 0) {
            return [];
        }

        $sortOrder = $this->sortOrderBuilder
            ->setField('created_at')
            ->setDirection(SortOrder::SORT_DESC)
            ->create();

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('product_id', $productId)
            ->addFilter('approved', 1)
            ->setPageSize($this->config->getReviewsLimit())
            ->setCurrentPage(1)
            ->addSortOrder($sortOrder)
            ->create();

        return $this->productReviewRepository
            ->getList($searchCriteria)
            ->getItems();
    }
}