<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProjectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('slug'),
                TextEntry::make('description'),
                TextEntry::make('content')
                    ->columnSpanFull(),
                TextEntry::make('thumbnail'),
                TextEntry::make('demo_url'),
                TextEntry::make('github_url'),
                TextEntry::make('status')
                    ->numeric(),
            ]);
    }
}
