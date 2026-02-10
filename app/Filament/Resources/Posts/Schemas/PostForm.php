<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Enums\ContentPostViewMode;
use App\Enums\PostType;
use App\Filament\Concerns\ExtractPlainTextFromRichEditor;
use App\Filament\Plugins\MediaIndexerRichContentPlugin;
use App\Filament\Plugins\ReferenceRichContentPlugin;
use App\Support\Post\Content;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{

    use ExtractPlainTextFromRichEditor;

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->required(),
                TextInput::make('slug')
                    ->copyable(copyMessage: 'Copied!', copyMessageDuration: 1500)
                    ->required(),
                Select::make('type')
                    ->options(PostType::class)
                    ->default('both')
                    ->required(),
                Select::make('category_id')
                    ->relationship(name: 'category', titleAttribute: 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                            ->required(),
                        TextInput::make('slug')
                            ->copyable(copyMessage: 'Copied!', copyMessageDuration: 1500)
                            ->required(),
                        ColorPicker::make('color')
                            ->default('#ebe2e2')
                            ->required(),
                    ])
                    ->createOptionAction(function (Action $action) {
                        return $action
                            ->label('Create Category')
                            ->modalWidth('md');
                    })
                    ->loadingMessage('Loading categories...')
                    ->required(),
                Select::make('tag_id')
                    ->relationship(name: 'tags', titleAttribute: 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                            ->required(),
                        TextInput::make('slug')
                            ->copyable(copyMessage: 'Copied!', copyMessageDuration: 1500)
                            ->required(),
                        ColorPicker::make('color')
                            ->default('#ebe2e2')
                            ->required(),
                    ])
                    ->createOptionAction(function (Action $action) {
                        return $action
                            ->label('Create Tag')
                            ->modalWidth('md');
                    })
                    ->loadingMessage('Loading tags...')
                    ->multiple()
                    ->required(),
                Repeater::make('contents')
                    ->relationship('contents')
                    ->schema([
                        RichEditor::make('body')
                            ->formatStateUsing(fn($state) => $state instanceof Content ? $state->toArray() : $state)
                            ->json()
                            ->live(onBlur: true)
                            ->plugins([ReferenceRichContentPlugin::make(), MediaIndexerRichContentPlugin::make()])
                            ->toolbarButtons([
                                ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript'],
                                ['h2', 'h3', 'alignStart', 'alignCenter', 'alignEnd'],
                                ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                                ['table', 'attachFiles'],
                                ['undo', 'redo', 'reference-link', 'media-indexer-link']
                            ])
                            ->afterStateUpdated(fn(Set $set, array | string $state) => $set('excerpt', Str::limit(self::extractPlainText($state), 200)))
                            ->columnSpanFull()
                            ->required(),
                        Select::make('view_mode')
                            ->options(ContentPostViewMode::class)
                            ->default(ContentPostViewMode::CONCEPT)
                            ->required()
                    ]),
                Textarea::make('excerpt')
                    ->required()
                    ->columnSpanFull(),
                Toggle::make('is_featured')
                    ->required(),
            ]);
    }
}
