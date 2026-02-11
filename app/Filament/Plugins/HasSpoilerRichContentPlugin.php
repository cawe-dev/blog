<?php

namespace App\Filament\Plugins;

use App\TiptapExtensions\HasSpoilerLink;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\EditorCommand;
use Filament\Forms\Components\RichEditor\Plugins\Contracts\RichContentPlugin;
use Filament\Forms\Components\RichEditor\RichEditorTool;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Icons\Heroicon;

class HasSpoilerRichContentPlugin implements RichContentPlugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getTipTapPhpExtensions(): array
    {
        return [
            app(HasSpoilerLink::class)
        ];
    }

    public function getTipTapJsExtensions(): array
    {
        return [
            FilamentAsset::getScriptSrc('has-spoiler-link-script'),
        ];
    }

    public function getEditorTools(): array
    {
        return [
            RichEditorTool::make('has-spoiler-link')
                ->icon(Heroicon::EyeSlash)
                ->action('has-spoiler-link'),
        ];
    }

    /**
     * @return array<Action>
     */
    public function getEditorActions(): array
    {
        return [
            Action::make('has-spoiler-link')
                ->action(function (array $arguments, array $data, RichEditor $component): void {
                    $component->runCommands(
                        [
                            EditorCommand::make(
                                'setHasSpoilerLink',
                                [
                                    [
                                        'spoiler' => 'true',
                                    ]
                                ],
                            ),
                            EditorCommand::make('focus', ['end']),
                            EditorCommand::make('unsetHasSpoilerLink'),
                        ],
                        editorSelection: $arguments['editorSelection'],
                    );
                })
        ];
    }
}
