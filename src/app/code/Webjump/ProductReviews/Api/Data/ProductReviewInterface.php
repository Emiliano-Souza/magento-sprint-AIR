<?php

declare(strict_types=1);

namespace Webjump\ProductReviews\Api\Data;

interface ProductReviewInterface
{
    public const REVIEW_ID = 'review_id';
    public const PRODUCT_ID = 'product_id';
    public const AUTHOR = 'author';
    public const COMMENT = 'comment';
    public const RATING = 'rating';
    public const APPROVED = 'approved';
    public const CREATED_AT = 'created_at';

    public function getReviewId(): ?int;

    public function setReviewId(int $reviewId): self;

    public function getProductId(): ?int;

    public function setProductId(int $productId): self;

    public function getAuthor(): ?string;

    public function setAuthor(string $author): self;

    public function getComment(): ?string;

    public function setComment(?string $comment): self;

    public function getRating(): ?int;

    public function setRating(int $rating): self;

    public function isApproved(): bool;

    public function setApproved(bool $approved): self;

    public function getCreatedAt(): ?string;

    public function setCreatedAt(string $createdAt): self;
}