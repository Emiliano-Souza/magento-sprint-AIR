<?php

declare(strict_types=1);

namespace Webjump\ProductReviews\Block\Adminhtml\Review\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    public function getButtonData(): array
    {
        $reviewId = $this->getReviewId();

        if ($reviewId === null) {
            return [];
        }

        return [
            'label' => __('Delete Review'),
            'class' => 'delete',
            'on_click' => sprintf(
                "deleteConfirm('%s', '%s')",
                __('Are you sure you want to delete this review?'),
                $this->getDeleteUrl($reviewId)
            ),
            'sort_order' => 20,
        ];
    }

    private function getDeleteUrl(int $reviewId): string
    {
        return $this->getUrl(
            '*/*/delete',
            ['review_id' => $reviewId]
        );
    }
}