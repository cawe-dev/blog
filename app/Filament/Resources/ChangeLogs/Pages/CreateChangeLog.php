<?php

namespace App\Filament\Resources\ChangeLogs\Pages;

use App\Filament\Resources\ChangeLogs\ChangeLogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateChangeLog extends CreateRecord
{
    protected static string $resource = ChangeLogResource::class;
}
