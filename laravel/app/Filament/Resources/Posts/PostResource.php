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
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
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
                    // CỘT TRÁI: Nội dung chính
                    Group::make([
                        Section::make('Nội dung bài viết')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Tiêu đề')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn(string $operation, $state, $set) =>
                                    $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                                TextInput::make('slug')
                                    ->label('URL động')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->unique(Post::class, 'slug', ignoreRecord: true),

                                MarkdownEditor::make('content')
                                    ->label('Nội dung Markdown')
                                    ->required(),
                            ]),
                    ])->grow(true),

                    // CỘT PHẢI: Thông tin phụ
                    Group::make([
                        Section::make('Ảnh & Mô tả')
                            ->schema([
                                TextInput::make('cover_image') // Khớp với model của Vũ
                                    ->label('Link ảnh bìa (GitHub/Imgur)')
                                    ->placeholder('Dán link ảnh...')
                                    ->required(),

                                Textarea::make('summary') // Dùng textarea cho summary sẽ hợp lý hơn
                                    ->label('Tóm tắt ngắn')
                                    ->rows(3)
                                    ->required(),
                            ]),

                        Section::make('Trạng thái')
                            ->schema([
                                Toggle::make('is_published')
                                    ->label('Công khai bài viết')
                                    ->default(true),

                                DateTimePicker::make('published_at')
                                    ->label('Ngày xuất bản')
                                    ->default(now()),

                                TextInput::make('views')
                                    ->label('Lượt xem ban đầu')
                                    ->numeric()
                                    ->default(0),
                            ]),
                    ])->grow(false),
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
                ImageColumn::make('cover_image')
                    ->label('Ảnh bìa')
                    ->disk('supabase') // BẮT BUỘC: Để nó biết lấy ảnh từ Supabase
                    ->circular(),

                TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->sortable()
                    ->wrap(), // Tiêu đề dài sẽ tự xuống dòng

                IconColumn::make('is_published')
                    ->label('Đã đăng')
                    ->boolean() // Hiện dấu tích xanh/đỏ
                    ->sortable(),

                TextColumn::make('views')
                    ->label('Lượt xem')
                    ->numeric()
                    ->sortable()
                    ->badge(), // Hiện dạng tag cho đẹp

                TextColumn::make('published_at')
                    ->label('Ngày xuất bản')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('published_at', 'desc');
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
