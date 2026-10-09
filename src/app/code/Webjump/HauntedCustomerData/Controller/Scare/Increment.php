<?php

declare(strict_types=1);

namespace Webjump\HauntedCustomerData\Controller\Scare;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;

class Increment implements HttpPostActionInterface
{
    private const SESSION_KEY = 'haunted_scare_count';

    public function __construct(
        private readonly CustomerSession $customerSession,
        private readonly JsonFactory $resultJsonFactory
    ) {
    }

    public function execute(): Json
    {
        $total = (int) $this->customerSession->getData(self::SESSION_KEY);

        $this->customerSession->setData(
            self::SESSION_KEY,
            $total + 1
        );

        return $this->resultJsonFactory->create()->setData([
            'success' => true
        ]);
    }
}