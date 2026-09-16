<?php

declare(strict_types=1);

namespace Webjump\ProductReviews\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Webjump\ProductReviews\Api\Data\ProductReviewInterface;
use Webjump\ProductReviews\Api\Data\ProductReviewSearchResultsInterface;
use Webjump\ProductReviews\Api\ProductReviewRepositoryInterface;
use Webjump\ProductReviews\Model\ResourceModel\ProductReview as ProductReviewResource;
use Webjump\ProductReviews\Model\ResourceModel\ProductReview\CollectionFactory;

class ProductReviewRepository implements ProductReviewRepositoryInterface
{
    private const DEFAULT_PAGE_SIZE = 20;

    public function __construct(
        private readonly ProductReviewFactory $productReviewFactory,
        private readonly ProductReviewResource $resource,
        private readonly CollectionFactory $collectionFactory,
        private readonly ProductReviewSearchResultsFactory $searchResultsFactory,
        private readonly CollectionProcessorInterface $collectionProcessor
    ) {
    }

    public function save(
        ProductReviewInterface $productReview
    ): ProductReviewInterface {
        if (!$productReview instanceof ProductReview) {
            throw new CouldNotSaveException(
                __('Invalid product review implementation.')
            );
        }

        try {
            $this->resource->save($productReview);
        } catch (\Throwable $exception) {
            throw new CouldNotSaveException(
                __('Could not save the product review.'),
                $exception
            );
        }

        return $productReview;
    }

    public function getById(int $reviewId): ProductReviewInterface
    {
        $productReview = $this->productReviewFactory->create();

        $this->resource->load($productReview, $reviewId);

        if (!$productReview->getId()) {
            throw new NoSuchEntityException(
                __('Product review with ID "%1" does not exist.', $reviewId)
            );
        }

        return $productReview;
    }

    public function delete(
        ProductReviewInterface $productReview
    ): bool {
        if (!$productReview instanceof ProductReview) {
            throw new CouldNotDeleteException(
                __('Invalid product review implementation.')
            );
        }

        try {
            $this->resource->delete($productReview);
        } catch (\Throwable $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete the product review.'),
                $exception
            );
        }

        return true;
    }

    public function getList(
        SearchCriteriaInterface $searchCriteria
    ): ProductReviewSearchResultsInterface {
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process(
            $searchCriteria,
            $collection
        );

        if (!$searchCriteria->getPageSize()) {
            $collection->setPageSize(self::DEFAULT_PAGE_SIZE);
        }

        $searchResults = $this->searchResultsFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount((int) $collection->getSize());

        return $searchResults;
    }
}