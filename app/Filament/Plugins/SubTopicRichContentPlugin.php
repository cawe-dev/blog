<?php

namespace App\Filament\Plugins;

use App\TiptapExtensions\SubTopicLink;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\EditorCommand;
use Filament\Forms\Components\RichEditor\Plugins\Contracts\RichContentPlugin;
use Filament\Forms\Components\RichEditor\RichEditorTool;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class SubTopicRichContentPlugin implements RichContentPlugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getTipTapPhpExtensions(): array
    {
        return [
            app(SubTopicLink::class),
        ];
    }

    public function getTipTapJsExtensions(): array
    {
        return [
            FilamentAsset::getScriptSrc('sub-topic-link-script'),
        ];
    }

    public function getEditorTools(): array
    {
        return [
            RichEditorTool::make('sub-topic-link')
                ->icon(Heroicon::DocumentText)
                ->action('sub-topic-link', arguments: '{ subTopicId: $getEditor().state.doc.textBetween($getEditor().state.selection.from, $getEditor().state.selection.to) }'),
        ];
    }

    /**
     * @return array<Action>
     */
    public function getEditorActions(): array
    {
        return [
            Action::make('sub-topic-link')
                ->action(function (array $arguments, RichEditor $component): void {
                    $component->runCommands(
                        [
                            EditorCommand::make(
                                'setSubTopicLink',
                                [
                                    [
                                        'subTopicId' => (string) Str::slug($arguments['subTopicId']),
                                    ],
                                ],
                            ),
                            EditorCommand::make('focus', ['end']),
                            EditorCommand::make('unsetSubTopicLink'),
                        ],
                        editorSelection: $arguments['editorSelection'],
                    );
                }),
        ];
    }
}
