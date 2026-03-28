<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PostInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('slug'),
                TextEntry::make('summary'),
                TextEntry::make('content')
                    ->columnSpanFull(),
                ImageEntry::make('cover_image'),
                TextEntry::make('views')
                    ->numeric(),
            ]);
    }
}
