<?php

namespace App\Filament\Resources\ChangeLogs\Pages;

use App\Filament\Resources\ChangeLogs\ChangeLogResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditChangeLog extends EditRecord
{
    protected static string $resource = ChangeLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
