<?php
declare(strict_types=1);

namespace Webjump\HauntedCheckout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\Quote;
use Magento\Sales\Model\Order;

class CopyHauntedMessageToOrder implements ObserverInterface
{
    public function execute(Observer $observer): void
    {
        /** @var Quote|null $quote */
        $quote = $observer->getEvent()->getQuote();

        /** @var Order|null $order */
        $order = $observer->getEvent()->getOrder();

        if (!$quote || !$order) {
            return;
        }

        $message = $quote->getData('haunted_message');

        $message = is_string($message)
            ? trim($message)
            : '';

        $order->setData(
            'haunted_message',
            $message !== '' ? $message : null
        );
    }
}
