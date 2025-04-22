<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ProductStatusEnum: string implements HasLabel
{
    case IN_STOCK = 'In Stock';
    case SOLD_OUT = 'Sold Out';
    case COMING_SOON = 'Coming Soon';

    public function getLabel(): ?string
    {
        return $this->value;
    }
}
