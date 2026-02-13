<?php

namespace App\Filament\Plugins;

use App\Models\Reference;
use App\TiptapExtensions\ReferenceLink;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor\EditorCommand;
use Filament\Forms\Components\RichEditor\Plugins\Contracts\RichContentPlugin;
use Filament\Forms\Components\RichEditor\RichEditorTool;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Enums\Width;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Icons\Heroicon;

class ReferenceRichContentPlugin implements RichContentPlugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getTipTapPhpExtensions(): array
    {
        return [
            app(ReferenceLink::class)
        ];
    }

    public function getTipTapJsExtensions(): array
    {
        return [
            FilamentAsset::getScriptSrc('reference-link-script'),
        ];
    }

    public function getEditorTools(): array
    {
        return [
            RichEditorTool::make('reference-link')
                ->icon(Heroicon::OutlinedBookOpen)
                ->action('reference-link', arguments: '{ term: $getEditor().state.doc.textBetween($getEditor().state.selection.from, $getEditor().state.selection.to) }'),
        ];
    }

    /**
     * @return array<Action>
     */
    public function getEditorActions(): array
    {
        return [
            Action::make('reference-link')
                ->modalWidth(Width::Large)
                ->fillForm(fn(array $arguments): array => [
                    'term' => $arguments['term'] ?? null,
                ])
                ->schema([
                    Toggle::make('is_new')
                        ->label('Add a new reference?')
                        ->live(onBlur: true)
                        ->default(false)
                        ->required(),
                    Select::make('reference_id')
                        ->hidden(fn(Get $get): bool => $get('is_new'))
                        ->options(fn() => Reference::pluck('title', 'id'))
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('parent_id')
                        ->label('Parente')
                        ->options(fn() => Reference::pluck('title', 'id'))
                        ->searchable()
                        ->preload()
                        ->hidden(fn(Get $get): bool => !$get('is_new')),
                    TextInput::make('title')
                        ->required()
                        ->hidden(fn(Get $get): bool => !$get('is_new')),
                    TextInput::make('url')
                        ->url()
                        ->hidden(fn(Get $get): bool => !$get('is_new')),
                    Textarea::make('description')
                        ->required()
                        ->hidden(fn(Get $get): bool => !$get('is_new')),
                    TextInput::make('media_url')
                        ->url()
                        ->hidden(fn(Get $get): bool => !$get('is_new')),
                    TextInput::make('context'),
                    TextInput::make('term')
                        ->readOnly()
                        ->required(),
                ])
                ->action(function (array $arguments, array $data, RichEditor $component): void {
                    $referenceId = $data['reference_id'] ?? null;

                    if ($data['is_new']) {
                        $newReference = Reference::create([
                            'title' => $data['title'],
                            'url' => $data['url'] ?? null,
                            'description' => $data['description'] ?? null,
                            'media_url' => $data['media_url'] ?? null,
                            'parent_id' => $data['parent_id'] ?? null,
                        ]);

                        $referenceId = $newReference->id;
                    }

                    $component->runCommands(
                        [
                            EditorCommand::make(
                                'setReferenceLink',
                                [
                                    [
                                        'referenceId' => (string) $referenceId,
                                        'term' => (string) ($data['term'] ?? ''),
                                        'context' => (string) ($data['context'] ?? ''),
                                    ]
                                ],
                            ),
                            EditorCommand::make('focus', ['end']),
                            EditorCommand::make('unsetReferenceLink'),
                        ],
                        editorSelection: $arguments['editorSelection'],
                    );
                })
        ];
    }
}
