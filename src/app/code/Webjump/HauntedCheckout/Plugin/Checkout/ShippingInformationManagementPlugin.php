<?php
declare(strict_types=1);

namespace Webjump\HauntedCheckout\Plugin\Checkout;

use Magento\Checkout\Api\Data\PaymentDetailsInterface;
use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Model\ShippingInformationManagement;
use Magento\Framework\Exception\InputException;
use Magento\Quote\Api\CartRepositoryInterface;

class ShippingInformationManagementPlugin
{
    private const MAX_MESSAGE_LENGTH = 200;

    public function __construct(
        private readonly CartRepositoryInterface $quoteRepository
    ) {
    }

    public function beforeSaveAddressInformation(
        ShippingInformationManagement $subject,
        $cartId,
        ShippingInformationInterface $addressInformation
    ): array {
        $message = $this->getHauntedMessage($addressInformation);

        if (mb_strlen($message) > self::MAX_MESSAGE_LENGTH) {
            throw new InputException(
                __('The haunted message must contain 200 characters or fewer.')
            );
        }

        return [
            $cartId,
            $addressInformation
        ];
    }

    public function afterSaveAddressInformation(
        ShippingInformationManagement $subject,
        PaymentDetailsInterface $result,
        $cartId,
        ShippingInformationInterface $addressInformation
    ): PaymentDetailsInterface {
        $message = $this->getHauntedMessage($addressInformation);

        $quote = $this->quoteRepository->getActive($cartId);

        $quote->setData(
            'haunted_message',
            $message !== '' ? $message : null
        );

        $this->quoteRepository->save($quote);

        return $result;
    }

    private function getHauntedMessage(
        ShippingInformationInterface $addressInformation
    ): string {
        $shippingAddress = $addressInformation->getShippingAddress();

        if (!$shippingAddress) {
            return '';
        }

        $extensionAttributes = $shippingAddress->getExtensionAttributes();

        if (!$extensionAttributes) {
            return '';
        }

        $message = $extensionAttributes->getHauntedMessage();

        return is_string($message)
            ? trim($message)
            : '';
    }
}