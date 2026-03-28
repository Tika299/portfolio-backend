<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('description')
                    ->required(),
                Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('thumbnail')
                    ->required(),
                TextInput::make('demo_url')
                    ->url()
                    ->required(),
                TextInput::make('github_url')
                    ->url()
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->numeric(),
            ]);
    }
}
