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
        $model = $this->getPersistableModel($productReview);

        try {
            $this->resource->save($model);
        } catch (\Throwable $exception) {
            throw new CouldNotSaveException(
                __('Could not save the product review.'),
                $exception
            );
        }

        return $model;
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
        $reviewId = $productReview->getReviewId();

        if ($reviewId === null) {
            throw new CouldNotDeleteException(
                __('Product review ID is required for deletion.')
            );
        }

        $model = $this->productReviewFactory->create();
        $this->resource->load($model, $reviewId);

        if (!$model->getId()) {
            throw new CouldNotDeleteException(
                __('Product review with ID "%1" does not exist.', $reviewId)
            );
        }

        try {
            $this->resource->delete($model);
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

        if ($searchCriteria->getPageSize() === null) {
            $searchCriteria->setPageSize(self::DEFAULT_PAGE_SIZE);
        }

        $this->collectionProcessor->process(
            $searchCriteria,
            $collection
        );

        $searchResults = $this->searchResultsFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount((int) $collection->getSize());

        return $searchResults;
    }

    private function getPersistableModel(
        ProductReviewInterface $productReview
    ): ProductReview {
        if ($productReview instanceof ProductReview) {
            return $productReview;
        }

        $model = $this->productReviewFactory->create();

        if ($productReview->getReviewId() !== null) {
            $model->setReviewId($productReview->getReviewId());
        }

        if ($productReview->getProductId() !== null) {
            $model->setProductId($productReview->getProductId());
        }

        if ($productReview->getAuthor() !== null) {
            $model->setAuthor($productReview->getAuthor());
        }

        $model->setComment($productReview->getComment());

        if ($productReview->getRating() !== null) {
            $model->setRating($productReview->getRating());
        }

        $model->setApproved($productReview->isApproved());

        if ($productReview->getCreatedAt() !== null) {
            $model->setCreatedAt($productReview->getCreatedAt());
        }

        return $model;
    }
}