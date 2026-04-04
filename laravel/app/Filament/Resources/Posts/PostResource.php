<?php

namespace App\Filament\Resources\Posts;

use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Filament\Resources\Posts\Pages\ListPosts;
use App\Filament\Resources\Posts\Pages\ViewPost;
use App\Filament\Resources\Posts\Schemas\PostForm;
use App\Filament\Resources\Posts\Schemas\PostInfolist;
use App\Filament\Resources\Posts\Tables\PostsTable;
use App\Models\Post;
use BackedEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Split::make([
                    // CỘT TRÁI: CHIẾM PHẦN LỚN (Nội dung chính)
                    Group::make([
                        Section::make('Soạn thảo nội dung')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Tiêu đề bài viết')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn(string $operation, $state, $set) =>
                                    $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                                TextInput::make('slug')
                                    ->label('URL động (Slug)')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),

                                MarkdownEditor::make('content')
                                    ->label('Nội dung bài viết')
                                    ->required()
                                    ->columnSpanFull(),
                            ])
                    ])->grow(true), // Cột này sẽ tự giãn rộng ra

                    // CỘT PHẢI: CHIẾM PHẦN NHỎ (Cấu hình & Sidebar)
                    Group::make([
                        Section::make('Hình ảnh & Tóm tắt')
                            ->schema([
                                TextInput::make('thumbnail')
                                    ->label('Link ảnh đại diện (GitHub/Imgur)')
                                    ->required(),

                                TextInput::make('summary')
                                    ->label('Tóm tắt bài viết')
                                    ->placeholder('Mô tả ngắn cho trang danh sách...')
                                    ->required(),
                            ]),

                        Section::make('Trạng thái xuất bản')
                            ->schema([
                                Toggle::make('is_published')
                                    ->label('Công khai')
                                    ->default(true),

                                DateTimePicker::make('published_at')
                                    ->label('Ngày đăng bài')
                                    ->default(now()),
                            ]),
                    ])->grow(false)->extraAttributes(['class' => 'w-full lg:w-[350px]']), // Cố định độ rộng sidebar
                ])->columnSpanFull(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PostInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')
                    ->label('Ảnh')
                    ->circular(),

                TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('slug')
                    ->label('Đường dẫn')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_published')
                    ->label('Trạng thái')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('published_at')
                    ->label('Ngày đăng')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('published_at', 'desc') // Mới nhất hiện lên đầu
            ->filters([
                // Thêm bộ lọc trạng thái
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'view' => ViewPost::route('/{record}'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }
}
