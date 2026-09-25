<?php

declare(strict_types=1);

namespace Webjump\ProductReviews\Controller\Adminhtml\Review;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Ui\Component\MassAction\Filter;
use Webjump\ProductReviews\Api\ProductReviewRepositoryInterface;
use Webjump\ProductReviews\Model\ResourceModel\ProductReview\CollectionFactory;

class MassApprove extends Action
{
    public const ADMIN_RESOURCE = 'Webjump_ProductReviews::reviews';

    public function __construct(
        Action\Context $context,
        private readonly Filter $filter,
        private readonly CollectionFactory $collectionFactory,
        private readonly ProductReviewRepositoryInterface $productReviewRepository
    ) {
        parent::__construct($context);
    }

    public function execute(): Redirect
    {
        $collection = $this->filter->getCollection(
            $this->collectionFactory->create()
        );

        $approvedCount = 0;

        foreach ($collection as $review) {
            try {
                $review->setApproved(true);
                $this->productReviewRepository->save($review);
                $approvedCount++;
            } catch (\Throwable $exception) {
                $this->messageManager->addErrorMessage(
                    __('Could not approve review ID %1.', $review->getId())
                );
            }
        }

        if ($approvedCount > 0) {
            $this->messageManager->addSuccessMessage(
                __('%1 review(s) approved.', $approvedCount)
            );
        }

        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
}