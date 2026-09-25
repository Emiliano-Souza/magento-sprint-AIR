<?php

declare(strict_types=1);

namespace Webjump\ProductReviews\Model\Export;

use Magento\Framework\Api\Search\DocumentInterface;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Convert\Excel;
use Magento\Framework\Convert\ExcelFactory;
use Magento\Framework\Filesystem;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Ui\Model\Export\ConvertToXml as MagentoConvertToXml;
use Magento\Ui\Model\Export\MetadataProvider;
use Magento\Ui\Model\Export\SearchResultIterator;
use Magento\Ui\Model\Export\SearchResultIteratorFactory;

class ConvertToXml extends MagentoConvertToXml
{
    public function __construct(
        Filesystem $filesystem,
        Filter $filter,
        MetadataProvider $metadataProvider,
        ExcelFactory $excelFactory,
        SearchResultIteratorFactory $iteratorFactory,
        private readonly ProductReviewExportFormatter $formatter
    ) {
        parent::__construct(
            $filesystem,
            $filter,
            $metadataProvider,
            $excelFactory,
            $iteratorFactory
        );
    }

    public function getRowData(DocumentInterface $document)
    {
        $fields = $this->getFields();

        $row = $this->metadataProvider->getRowData(
            $document,
            $fields,
            $this->getOptions()
        );

        $approvedIndex = array_search(
            'approved',
            $fields,
            true
        );

        if ($approvedIndex !== false) {
            $approved = $document
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
            $createdAt = $document
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
            $document
                ->getCustomAttribute('product_id')
                ?->getValue()
            ?? 0
        );

        $row[] = $this->formatter->getProductName($productId);

        return $row;
    }

    public function getXmlFile()
    {
        $component = $this->filter->getComponent();

        $name = md5((string) microtime(true));
        $file = 'export/'
            . $component->getName()
            . $name
            . '.xml';

        $this->filter->prepareComponent($component);
        $this->filter->applySelectionOnTargetProvider();

        $component
            ->getContext()
            ->getDataProvider()
            ->setLimit(0, 0);

        /** @var SearchResultInterface $searchResult */
        $searchResult = $component
            ->getContext()
            ->getDataProvider()
            ->getSearchResult();

        /** @var DocumentInterface[] $searchResultItems */
        $searchResultItems = $searchResult->getItems();

        /** @var SearchResultIterator $searchResultIterator */
        $searchResultIterator = $this->iteratorFactory->create([
            'items' => $searchResultItems
        ]);

        /** @var Excel $excel */
        $excel = $this->excelFactory->create([
            'iterator' => $searchResultIterator,
            'rowCallback' => [$this, 'getRowData'],
        ]);

        $this->directory->create('export');

        $stream = $this->directory->openFile($file, 'w+');
        $stream->lock();

        $headers = $this->metadataProvider->getHeaders($component);
        $headers[] = __('Product Name');

        $excel->setDataHeader($headers);
        $excel->write(
            $stream,
            $component->getName() . '.xml'
        );

        $stream->unlock();
        $stream->close();

        return [
            'type' => 'filename',
            'value' => $file,
            'rm' => true,
        ];
    }
}