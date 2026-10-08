<?php
declare(strict_types=1);

namespace Webjump\HauntedCheckout\Block\Adminhtml\Order\View;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\OrderRepositoryInterface;

class HauntedMessage extends Template
{
    public function __construct(
        Context $context,
        private readonly OrderRepositoryInterface $orderRepository,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function getHauntedMessage(): string
    {
        $orderId = (int) $this->getRequest()->getParam('order_id');

        if ($orderId <= 0) {
            return '';
        }

        try {
            $order = $this->orderRepository->get($orderId);
        } catch (NoSuchEntityException) {
            return '';
        }

        return trim(
            (string) $order->getData('haunted_message')
        );
    }
}
