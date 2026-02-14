<?php

namespace App\Filament\Resources\ChangeLogs\Pages;

use App\Filament\Resources\ChangeLogs\ChangeLogResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewChangeLog extends ViewRecord
{
    protected static string $resource = ChangeLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
