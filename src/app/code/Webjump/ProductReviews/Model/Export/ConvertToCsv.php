<?php

declare(strict_types=1);

namespace Webjump\ProductReviews\Model\Export;

use Magento\Framework\Filesystem;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Ui\Model\Export\ConvertToCsv as MagentoConvertToCsv;
use Magento\Ui\Model\Export\MetadataProvider;

class ConvertToCsv extends MagentoConvertToCsv
{
    public function __construct(
        Filesystem $filesystem,
        Filter $filter,
        MetadataProvider $metadataProvider,
        private readonly ProductReviewExportFormatter $formatter,
        int $pageSize = 200
    ) {
        parent::__construct(
            $filesystem,
            $filter,
            $metadataProvider,
            $pageSize
        );
    }

    public function getCsvFile()
    {
        $component = $this->filter->getComponent();

        $fileName = 'export/' . $component->getName()
            . md5((string) microtime(true))
            . '.csv';

        $this->filter->prepareComponent($component);
        $this->filter->applySelectionOnTargetProvider();

        $dataProvider = $component->getContext()->getDataProvider();

        $fields = $this->metadataProvider->getFields($component);
        $options = $this->metadataProvider->getOptions();

        $this->directory->create('export');

        $stream = $this->directory->openFile($fileName, 'w+');
        $stream->lock();

        $headers = $this->metadataProvider->getHeaders($component);
        $headers[] = __('Product Name');

        $stream->writeCsv($headers);

        $currentPage = 1;

        $searchCriteria = $dataProvider
            ->getSearchCriteria()
            ->setCurrentPage($currentPage)
            ->setPageSize($this->pageSize);

        $totalCount = (int) $dataProvider
            ->getSearchResult()
            ->getTotalCount();

        while ($totalCount > 0) {
            $items = $dataProvider
                ->getSearchResult()
                ->getItems();

            foreach ($items as $item) {
                $row = $this->metadataProvider->getRowData(
                    $item,
                    $fields,
                    $options
                );

                $approvedIndex = array_search(
                    'approved',
                    $fields,
                    true
                );

                if ($approvedIndex !== false) {
                    $approved = $item
                        ->getCustomAttribute('approved')
                        ?->getValue();

                    $row[$approvedIndex] =
                        $this->formatter->formatApproved($approved);
                }

                $createdAtIndex = array_search(
                    'created_at',
                    $fields,
                    true
                );

                if ($createdAtIndex !== false) {
                    $createdAt = $item
                        ->getCustomAttribute('created_at')
                        ?->getValue();

                    $row[$createdAtIndex] =
                        $this->formatter->formatCreatedAt(
                            $createdAt !== null
                                ? (string) $createdAt
                                : null
                        );
                }

                $productId = (int) (
                    $item
                        ->getCustomAttribute('product_id')
                        ?->getValue()
                    ?? 0
                );

                $row[] = $this->formatter->getProductName($productId);

                $stream->writeCsv($row);
            }

            $searchCriteria->setCurrentPage(++$currentPage);
            $totalCount -= $this->pageSize;
        }

        $stream->unlock();
        $stream->close();

        return [
            'type' => 'filename',
            'value' => $fileName,
            'rm' => true,
        ];
    }
}