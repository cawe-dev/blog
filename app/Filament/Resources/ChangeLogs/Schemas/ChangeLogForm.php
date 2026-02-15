<?php

namespace App\Filament\Resources\ChangeLogs\Schemas;

use App\Enums\ChangeLogType;
use App\Services\Github\GitHubService;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
                            CheckboxList::make("commits")
                                ->options(
                                    fn(GitHubService $service) =>
                                    $service->commitsToOptions($service->getCommitsByBranch("development"))
                                )
                                ->searchable()
                                ->live()
                                ->afterStateUpdated(
                                    fn(Set $set, $state, GitHubService $service) =>
                                    self::updateFromCommit($set, $state, $service)
                                ),
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
