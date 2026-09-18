<?php

declare(strict_types=1);

namespace Webjump\ProductReviews\Setup\Patch\Data;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductFactory as CatalogProductFactory;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Webjump\ProductReviews\Api\ProductReviewRepositoryInterface;
use Webjump\ProductReviews\Model\ProductReviewFactory;
use Webjump\ProductReviews\Model\ResourceModel\ProductReview\CollectionFactory as ReviewCollectionFactory;

class AddSampleProductReviews implements DataPatchInterface
{
    private const SAMPLE_PRODUCT_SKU = 'webjump-review-sample';

    public function __construct(
        private readonly ProductCollectionFactory $productCollectionFactory,
        private readonly CatalogProductFactory $catalogProductFactory,
        private readonly ProductRepositoryInterface $catalogProductRepository,
        private readonly EavConfig $eavConfig,
        private readonly ProductReviewFactory $productReviewFactory,
        private readonly ProductReviewRepositoryInterface $productReviewRepository,
        private readonly ReviewCollectionFactory $reviewCollectionFactory
    ) {
    }

    public function apply(): void
    {
        $productId = $this->getProductId();

        $reviews = [
            [
                'author' => 'Ana Souza',
                'comment' => 'Produto excelente e muito bem apresentado.',
                'rating' => 5,
                'approved' => true,
            ],
            [
                'author' => 'Carlos Lima',
                'comment' => 'Boa experiência de compra e produto de qualidade.',
                'rating' => 4,
                'approved' => true,
            ],
            [
                'author' => 'Mariana Alves',
                'comment' => 'Gostei do produto, compraria novamente.',
                'rating' => 5,
                'approved' => true,
            ],
            [
                'author' => 'Lucas Ferreira',
                'comment' => 'Produto atende ao esperado.',
                'rating' => 4,
                'approved' => false,
            ],
            [
                'author' => 'Juliana Costa',
                'comment' => 'Avaliação aguardando análise da equipe.',
                'rating' => 3,
                'approved' => false,
            ],
        ];

        foreach ($reviews as $reviewData) {
            if ($this->sampleReviewExists(
                $productId,
                $reviewData['author'],
                $reviewData['comment']
            )) {
                continue;
            }

            $review = $this->productReviewFactory->create();

            $review->setProductId($productId);
            $review->setAuthor($reviewData['author']);
            $review->setComment($reviewData['comment']);
            $review->setRating($reviewData['rating']);
            $review->setApproved($reviewData['approved']);

            $this->productReviewRepository->save($review);
        }
    }

    private function getProductId(): int
    {
        $collection = $this->productCollectionFactory->create();

        $collection
            ->setPageSize(1)
            ->setCurPage(1);

        $product = $collection->getFirstItem();

        if ($product->getId()) {
            return (int) $product->getId();
        }

        return $this->createSampleProduct();
    }

    private function createSampleProduct(): int
    {
        $entityType = $this->eavConfig->getEntityType(Product::ENTITY);

        $product = $this->catalogProductFactory->create();

        $product->setSku(self::SAMPLE_PRODUCT_SKU);
        $product->setName('Webjump Review Sample Product');
        $product->setAttributeSetId(
            (int) $entityType->getDefaultAttributeSetId()
        );
        $product->setTypeId(Type::TYPE_SIMPLE);
        $product->setStatus(Status::STATUS_DISABLED);
        $product->setVisibility(Visibility::VISIBILITY_NOT_VISIBLE);
        $product->setPrice(0);
        $product->setTaxClassId(0);

        $savedProduct = $this->catalogProductRepository->save($product);

        return (int) $savedProduct->getId();
    }

    private function sampleReviewExists(
        int $productId,
        string $author,
        string $comment
    ): bool {
        $collection = $this->reviewCollectionFactory->create();

        $collection
            ->addFieldToFilter('product_id', $productId)
            ->addFieldToFilter('author', $author)
            ->addFieldToFilter('comment', $comment)
            ->setPageSize(1)
            ->setCurPage(1);

        return (bool) $collection->getFirstItem()->getId();
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }
}