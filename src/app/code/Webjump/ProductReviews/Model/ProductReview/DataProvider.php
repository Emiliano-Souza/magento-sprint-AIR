<?php

declare(strict_types=1);

namespace Webjump\ProductReviews\Model\ProductReview;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Webjump\ProductReviews\Model\ResourceModel\ProductReview\CollectionFactory;

class DataProvider extends AbstractDataProvider
{
    private array $loadedData = [];

    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();

        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data
        );
    }

    public function getData(): array
    {
        if ($this->loadedData !== []) {
            return $this->loadedData;
        }

        foreach ($this->collection->getItems() as $review) {
            $this->loadedData[(int) $review->getId()] = $review->getData();
        }

        return $this->loadedData;
    }
}