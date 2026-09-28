<?php

declare(strict_types=1);

namespace Webjump\ProductReviews\Controller\Adminhtml\Review;

use Magento\Backend\App\Action;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Webjump\ProductReviews\Api\ProductReviewRepositoryInterface;

class Edit extends Action
{
    public const ADMIN_RESOURCE = 'Webjump_ProductReviews::reviews';

    public function __construct(
        Action\Context $context,
        private readonly PageFactory $resultPageFactory,
        private readonly ProductReviewRepositoryInterface $productReviewRepository
    ) {
        parent::__construct($context);
    }

    public function execute(): Page
    {
        $reviewId = (int) $this->getRequest()->getParam('review_id');

        if ($reviewId) {
            try {
                $this->productReviewRepository->getById($reviewId);
            } catch (NoSuchEntityException $exception) {
                $this->messageManager->addErrorMessage(
                    __('The product review no longer exists.')
                );

                return $this->resultPageFactory->create();
            }
        }

        $page = $this->resultPageFactory->create();

        $page->setActiveMenu('Webjump_ProductReviews::reviews');

        $page->getConfig()->getTitle()->prepend(
            $reviewId ? __('Edit Review') : __('New Review')
        );

        return $page;
    }
}