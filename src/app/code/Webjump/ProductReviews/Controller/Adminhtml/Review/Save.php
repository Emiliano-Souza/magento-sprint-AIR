<?php

declare(strict_types=1);

namespace Webjump\ProductReviews\Controller\Adminhtml\Review;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Webjump\ProductReviews\Api\Data\ProductReviewInterface;
use Webjump\ProductReviews\Api\ProductReviewRepositoryInterface;
use Webjump\ProductReviews\Model\ProductReviewFactory;

class Save extends Action
{
    public const ADMIN_RESOURCE = 'Webjump_ProductReviews::reviews';

    public function __construct(
        Action\Context $context,
        private readonly ProductReviewRepositoryInterface $productReviewRepository,
        private readonly ProductReviewFactory $productReviewFactory
    ) {
        parent::__construct($context);
    }

    public function execute(): Redirect
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if (!$data) {
            return $resultRedirect->setPath('*/*/');
        }

        $reviewId = isset($data['review_id'])
            ? (int) $data['review_id']
            : 0;

        try {
            $productId = isset($data['product_id'])
                ? (int) $data['product_id']
                : 0;

            $author = trim((string) ($data['author'] ?? ''));
            $comment = trim((string) ($data['comment'] ?? ''));
            $rating = isset($data['rating'])
                ? (int) $data['rating']
                : 0;

            if ($productId <= 0) {
                throw new \InvalidArgumentException(
                    (string) __('Product ID is required.')
                );
            }

            if ($author === '') {
                throw new \InvalidArgumentException(
                    (string) __('Author is required.')
                );
            }

            if ($rating < 1 || $rating > 5) {
                throw new \InvalidArgumentException(
                    (string) __('Rating must be between 1 and 5.')
                );
            }

            $review = $this->getReview($reviewId);

            $review->setProductId($productId);
            $review->setAuthor($author);
            $review->setComment($comment !== '' ? $comment : null);
            $review->setRating($rating);
            $review->setApproved(
                (bool) (int) ($data['approved'] ?? 0)
            );

            $this->productReviewRepository->save($review);

            $this->messageManager->addSuccessMessage(
                __('The product review has been saved.')
            );

            return $resultRedirect->setPath('*/*/');
        } catch (NoSuchEntityException $exception) {
            $this->messageManager->addErrorMessage(
                __('The product review no longer exists.')
            );
        } catch (CouldNotSaveException $exception) {
            $this->messageManager->addErrorMessage(
                __('Could not save the product review.')
            );
        } catch (\InvalidArgumentException $exception) {
            $this->messageManager->addErrorMessage(
                $exception->getMessage()
            );
        } catch (\Throwable $exception) {
            $this->messageManager->addErrorMessage(
                __('An error occurred while saving the product review.')
            );
        }

        if ($reviewId > 0) {
            return $resultRedirect->setPath(
                '*/*/edit',
                ['review_id' => $reviewId]
            );
        }

        return $resultRedirect->setPath('*/*/new');
    }

    private function getReview(int $reviewId): ProductReviewInterface
    {
        if ($reviewId > 0) {
            return $this->productReviewRepository->getById($reviewId);
        }

        return $this->productReviewFactory->create();
    }
}