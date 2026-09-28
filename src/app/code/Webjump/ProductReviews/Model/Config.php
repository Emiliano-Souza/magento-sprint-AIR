<?php

declare(strict_types=1);

namespace Webjump\ProductReviews\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    private const XML_PATH_ENABLED =
        'webjump_productreviews/general/enabled';

    private const XML_PATH_REVIEWS_LIMIT =
        'webjump_productreviews/general/reviews_limit';

    private const DEFAULT_REVIEWS_LIMIT = 5;

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    public function isEnabled(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getReviewsLimit(?int $storeId = null): int
    {
        $limit = (int) $this->scopeConfig->getValue(
            self::XML_PATH_REVIEWS_LIMIT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        return $limit > 0
            ? $limit
            : self::DEFAULT_REVIEWS_LIMIT;
    }
}