<?php

declare(strict_types=1);

namespace Webjump\ProductReviews\Controller\Adminhtml\Review;

use Magento\Backend\App\Action;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Ui\Model\Export\ConvertToXml;

class ExportXml extends Action
{
    public const ADMIN_RESOURCE = 'Webjump_ProductReviews::reviews_export';

    public function __construct(
        Action\Context $context,
        private readonly FileFactory $fileFactory,
        private readonly ConvertToXml $converter
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        return $this->fileFactory->create(
            'product-reviews.xml',
            $this->converter->getXmlFile(),
            'var'
        );
    }
}