<?php

namespace App\Filament\Resources\ChangeLogs\Schemas;

use App\Enums\ChangeLogType;
use App\Services\Github\GitHubService;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;

class ChangeLogForm
{

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Commit Select')
                        ->schema([
                            TextInput::make('current_commit_page')
                                ->default(1)
                                ->live()
                                ->readOnly(),
                            CheckboxList::make("commits")
                                ->options(function (Get $get, GitHubService $service) {
                                    $page = (int) ($get('current_commit_page') ?? 1);

                                    return $service->commitsToOptions(
                                        $service->getCommitsByBranch("development", $page)
                                    );
                                })
                                ->searchable()
                                ->live()
                                ->afterStateUpdated(
                                    fn(Set $set, $state, GitHubService $service) =>
                                    self::updateFromCommit($set, $state, $service)
                                ),
                            Flex::make([
                                Action::make("previous-page")
                                    ->action(function (Set $set, Get $get) {
                                        $page = (int) $get('current_commit_page');
                                        $page > 1 ? $set('current_commit_page', $page - 1) : $set('current_commit_page', 1);
                                    })
                                    ->color('secondary'),
                                Action::make("next-page")
                                    ->action(
                                        function (Set $set, Get $get) {
                                            $page = (int) $get('current_commit_page');
                                            $set('current_commit_page', $page + 1);
                                        }
                                    )
                                    ->color('accented'),
                            ]),
                        ]),
                    Step::make('Informations')
                        ->schema([
                            TextInput::make('title')
                                ->required(),
                            Select::make('type')
                                ->options(ChangeLogType::class)
                                ->required(),
                            TextInput::make('version')
                                ->required(),
                            TextInput::make('commit')
                                ->required(),
                            TextInput::make('pull_request'),
                            Select::make('post_id')
                                ->relationship('post', 'slug'),
                            DateTimePicker::make('published_at')
                        ]),
                ])
                    ->columnSpanFull()
            ]);
    }

    public static function updateFromCommit(Set $set, ?array $state, GitHubService $service): void
    {
        $lastSha = collect($state)->last();
        if (! $lastSha) return;

        $commit = $service->getCommitsByBranch("development")->firstWhere('sha', $lastSha);

        if ($commit) {
            $set('title', $commit->message);
            $set('commit', $lastSha);
        }
    }
}
