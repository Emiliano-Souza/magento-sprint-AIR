<?php

declare(strict_types=1);

namespace Webjump\ProductReviews\Controller\Adminhtml\Review;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Webjump\ProductReviews\Api\ProductReviewRepositoryInterface;

class Delete extends Action
{
    public const ADMIN_RESOURCE = 'Webjump_ProductReviews::reviews';

    public function __construct(
        Action\Context $context,
        private readonly ProductReviewRepositoryInterface $productReviewRepository
    ) {
        parent::__construct($context);
    }

    public function execute(): Redirect
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $reviewId = (int) $this->getRequest()->getParam('review_id');

        if ($reviewId <= 0) {
            $this->messageManager->addErrorMessage(
                __('Product review ID is required.')
            );

            return $resultRedirect->setPath('*/*/');
        }

        try {
            $review = $this->productReviewRepository->getById($reviewId);

            $this->productReviewRepository->delete($review);

            $this->messageManager->addSuccessMessage(
                __('The product review has been deleted.')
            );
        } catch (NoSuchEntityException $exception) {
            $this->messageManager->addErrorMessage(
                __('The product review no longer exists.')
            );
        } catch (CouldNotDeleteException $exception) {
            $this->messageManager->addErrorMessage(
                __('Could not delete the product review.')
            );
        } catch (\Throwable $exception) {
            $this->messageManager->addErrorMessage(
                __('An error occurred while deleting the product review.')
            );
        }

        return $resultRedirect->setPath('*/*/');
    }
}