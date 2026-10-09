<?php

declare(strict_types=1);

namespace Webjump\HauntedCustomerData\CustomerData;

use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Customer\Model\Session as CustomerSession;

class ScareCounter implements SectionSourceInterface
{
    private const SESSION_KEY = 'haunted_scare_count';

    public function __construct(
        private readonly CustomerSession $customerSession
    ) {
    }

    public function getSectionData(): array
    {
        return [
            'total' => (int) $this->customerSession->getData(self::SESSION_KEY)
        ];
    }
}