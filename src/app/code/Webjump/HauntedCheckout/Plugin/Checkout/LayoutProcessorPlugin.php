<?php
declare(strict_types=1);

namespace Webjump\HauntedCheckout\Plugin\Checkout;

use Magento\Checkout\Block\Checkout\LayoutProcessor;

class LayoutProcessorPlugin
{
    private const MAX_MESSAGE_LENGTH = 200;

    public function afterProcess(
        LayoutProcessor $subject,
        array $jsLayout
    ): array {
        $fields = &$jsLayout['components']['checkout']['children']
            ['steps']['children']
            ['shipping-step']['children']
            ['shippingAddress']['children']
            ['shipping-address-fieldset']['children'];

        $fields['haunted_message'] = [
            'component' => 'Magento_Ui/js/form/element/textarea',
            'config' => [
                'customScope' => 'shippingAddress',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/textarea',
            ],
            'dataScope' => 'shippingAddress.haunted_message',
            'label' => __('Haunted message for the package'),
            'provider' => 'checkoutProvider',
            'sortOrder' => 250,
            'validation' => [
                'max_text_length' => self::MAX_MESSAGE_LENGTH,
            ],
            'notice' => __('Maximum of 200 characters.'),
        ];

        return $jsLayout;
    }
}
