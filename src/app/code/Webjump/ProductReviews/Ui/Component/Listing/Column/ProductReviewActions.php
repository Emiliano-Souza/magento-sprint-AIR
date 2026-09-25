<?php

declare(strict_types=1);

namespace Webjump\ProductReviews\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class ProductReviewActions extends Column
{
    private const EDIT_URL_PATH = 'webjump_productreviews/review/edit';

    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        private readonly UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        parent::__construct(
            $context,
            $uiComponentFactory,
            $components,
            $data
        );
    }

    public function prepareDataSource(array $dataSource): array
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        foreach ($dataSource['data']['items'] as &$item) {
            if (!isset($item['review_id'])) {
                continue;
            }

            $item[$this->getData('name')]['edit'] = [
                'href' => $this->urlBuilder->getUrl(
                    self::EDIT_URL_PATH,
                    ['review_id' => $item['review_id']]
                ),
                'label' => __('Edit'),
            ];
        }

        return $dataSource;
    }
}