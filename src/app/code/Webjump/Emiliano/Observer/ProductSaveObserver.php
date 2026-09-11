<?php

declare(strict_types=1);

namespace Webjump\Emiliano\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

class ProductSaveObserver implements ObserverInterface
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    public function execute(Observer $observer): void
    {
        $product = $observer->getEvent()->getProduct();

        $this->logger->info(
            'Oak & Barrel: product saved.',
            [
                'product_id' => $product->getId(),
                'sku' => $product->getSku(),
                'name' => $product->getName(),
            ]
        );
    }
}