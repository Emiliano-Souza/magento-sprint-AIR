<?php

declare(strict_types=1);

namespace Webjump\ProductReviews\Block\Adminhtml\Review\Edit;

use Magento\Backend\Block\Widget\Context;

abstract class GenericButton
{
    public function __construct(
        protected readonly Context $context
    ) {
    }

    public function getReviewId(): ?int
    {
        $reviewId = (int) $this->context
            ->getRequest()
            ->getParam('review_id');

        return $reviewId > 0 ? $reviewId : null;
    }

    public function getUrl(
        string $route = '',
        array $params = []
    ): string {
        return $this->context
            ->getUrlBuilder()
            ->getUrl($route, $params);
    }
}