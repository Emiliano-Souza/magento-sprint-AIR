<?php

declare(strict_types=1);

namespace Webjump\ProductReviews\Model\Export;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class ProductReviewExportFormatter
{
    /**
     * @var array<int, string>
     */
    private array $productNames = [];

    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly TimezoneInterface $timezone
    ) {
    }

    public function formatApproved(mixed $approved): string
    {
        return (int) $approved === 1 ? 'Sim' : 'Não';
    }

    public function formatCreatedAt(?string $createdAt): string
    {
        if (!$createdAt) {
            return '';
        }

        try {
            return $this->timezone
                ->date(new \DateTime($createdAt))
                ->format('d/m/Y H:i:s');
        } catch (\Exception) {
            return $createdAt;
        }
    }

    public function getProductName(int $productId): string
    {
        if ($productId <= 0) {
            return '';
        }

        if (isset($this->productNames[$productId])) {
            return $this->productNames[$productId];
        }

        try {
            $product = $this->productRepository->getById($productId);

            return $this->productNames[$productId]
                = (string) $product->getName();
        } catch (NoSuchEntityException) {
            return $this->productNames[$productId]
                = 'Produto não encontrado';
        }
    }
}