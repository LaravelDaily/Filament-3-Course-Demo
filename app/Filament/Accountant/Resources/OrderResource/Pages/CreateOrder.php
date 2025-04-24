<?php

namespace App\Filament\Accountant\Resources\OrderResource\Pages;

use App\Filament\Accountant\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
}
