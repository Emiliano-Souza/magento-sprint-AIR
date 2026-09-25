<?php

declare(strict_types=1);

namespace Webjump\ProductReviews\Controller\Adminhtml\Review;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    public const ADMIN_RESOURCE = 'Webjump_ProductReviews::reviews';

    public function __construct(
        Action\Context $context,
        private readonly PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
    }

    public function execute(): Page
    {
        $page = $this->resultPageFactory->create();

        $page->setActiveMenu('Webjump_ProductReviews::reviews');
        $page->getConfig()->getTitle()->prepend(__('Product Reviews'));

        return $page;
    }
}