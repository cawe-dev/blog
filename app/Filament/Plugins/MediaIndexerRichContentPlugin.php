<?php

namespace App\Filament\Plugins;

use App\TiptapExtensions\MediaIndexerLink;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\EditorCommand;
use Filament\Forms\Components\RichEditor\Plugins\Contracts\RichContentPlugin;
use Filament\Forms\Components\RichEditor\RichEditorTool;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Support\Enums\Width;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Icons\Heroicon;

class MediaIndexerRichContentPlugin implements RichContentPlugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getTipTapPhpExtensions(): array
    {
        return [
            app(MediaIndexerLink::class)
        ];
    }

    public function getTipTapJsExtensions(): array
    {
        return [
            FilamentAsset::getScriptSrc('media-indexer-link-script'),
        ];
    }

    public function getEditorTools(): array
    {
        return [
            RichEditorTool::make('media-indexer-link')
                ->icon(Heroicon::Link)
                ->action('media-indexer-link'),
        ];
    }

    /**
     * @return array<Action>
     */
    public function getEditorActions(): array
    {
        return [
            Action::make('media-indexer-link')
                ->modalWidth(Width::Large)
                ->schema([
                    Toggle::make('is_image')
                        ->onIcon(Heroicon::Camera)
                        ->offIcon(Heroicon::VideoCamera)
                        ->label('Is video or image?')
                        ->live(onBlur: true)
                        ->default(false)
                        ->required(),
                    TextInput::make('url')
                        ->url(),
                ])
                ->action(function (array $arguments, array $data, RichEditor $component): void {
                    $component->runCommands(
                        [
                            EditorCommand::make(
                                'setMediaIndexerLink',
                                [
                                    [
                                        'type' => (string) ($data['is_image'] ? 'img' : 'iframe'),
                                        'url' => (string) ($data['url'] ?? ''),
                                    ]
                                ],
                            ),
                            EditorCommand::make('focus', ['end']),
                            EditorCommand::make('unsetMediaIndexerLink'),
                        ],
                        editorSelection: $arguments['editorSelection'],
                    );
                })
        ];
    }
}
