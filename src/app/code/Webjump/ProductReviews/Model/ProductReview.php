<?php

declare(strict_types=1);

namespace Webjump\ProductReviews\Model;

use Magento\Framework\Model\AbstractModel;
use Webjump\ProductReviews\Api\Data\ProductReviewInterface;
use Webjump\ProductReviews\Model\ResourceModel\ProductReview as ProductReviewResource;

class ProductReview extends AbstractModel implements ProductReviewInterface
{
    protected function _construct(): void
    {
        $this->_init(ProductReviewResource::class);
    }

    public function getReviewId(): ?int
    {
        $value = $this->getData(self::REVIEW_ID);

        return $value !== null ? (int) $value : null;
    }

    public function setReviewId(int $reviewId): self
    {
        return $this->setData(self::REVIEW_ID, $reviewId);
    }

    public function getProductId(): ?int
    {
        $value = $this->getData(self::PRODUCT_ID);

        return $value !== null ? (int) $value : null;
    }

    public function setProductId(int $productId): self
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    public function getAuthor(): ?string
    {
        $value = $this->getData(self::AUTHOR);

        return $value !== null ? (string) $value : null;
    }

    public function setAuthor(string $author): self
    {
        return $this->setData(self::AUTHOR, $author);
    }

    public function getComment(): ?string
    {
        $value = $this->getData(self::COMMENT);

        return $value !== null ? (string) $value : null;
    }

    public function setComment(?string $comment): self
    {
        return $this->setData(self::COMMENT, $comment);
    }

    public function getRating(): ?int
    {
        $value = $this->getData(self::RATING);

        return $value !== null ? (int) $value : null;
    }

    public function setRating(int $rating): self
    {
        return $this->setData(self::RATING, $rating);
    }

    public function isApproved(): bool
    {
        return (bool) $this->getData(self::APPROVED);
    }

    public function setApproved(bool $approved): self
    {
        return $this->setData(self::APPROVED, $approved);
    }

    public function getCreatedAt(): ?string
    {
        $value = $this->getData(self::CREATED_AT);

        return $value !== null ? (string) $value : null;
    }

    public function setCreatedAt(string $createdAt): self
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }
}