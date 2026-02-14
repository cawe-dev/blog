<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Concerns\SyncsPostReferences;
use App\Filament\Resources\Posts\PostResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePost extends CreateRecord
{
    use SyncsPostReferences;

    protected static string $resource = PostResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->syncReferences($this->record);
    }
}
