<?php

declare(strict_types=1);

namespace Webjump\Emiliano\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class HomeMessage implements ArgumentInterface
{
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
        return 'Uma seleção pensada para quem aprecia origem, tempo e personalidade em cada garrafa.';
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