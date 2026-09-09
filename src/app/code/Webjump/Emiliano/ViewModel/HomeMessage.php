<?php

declare(strict_types=1);

namespace Webjump\Emiliano\ViewModel;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\ScopeInterface;

class HomeMessage implements ArgumentInterface
{
    private const XML_PATH_MESSAGE = 'webjump_emiliano/home/message';

    private const DEFAULT_MESSAGE =
        'Uma seleção pensada para quem aprecia origem, tempo e personalidade em cada garrafa.';

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    public function getEyebrow(): string
    {
        return 'Oak & Barrel';
    }

    public function getTitle(): string
    {
        return 'Whiskies escolhidos para momentos especiais';
    }

    public function getMessage(): string
    {
        $message = (string) $this->scopeConfig->getValue(
            self::XML_PATH_MESSAGE,
            ScopeInterface::SCOPE_STORE
        );

        return trim($message) !== ''
            ? $message
            : self::DEFAULT_MESSAGE;
    }

    public function getCtaLabel(): string
    {
        return 'Conhecer a seleção';
    }

    public function getCtaUrl(): string
    {
        return '/whiskies.html';
    }

    public function getHighlights(): array
    {
        return [
            [
                'title' => 'Curadoria',
                'text' => 'Rótulos selecionados',
            ],
            [
                'title' => 'Origem',
                'text' => 'Diferentes regiões',
            ],
            [
                'title' => 'Experiência',
                'text' => 'Escolhas com personalidade',
            ],
        ];
    }
}