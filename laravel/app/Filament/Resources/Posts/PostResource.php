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
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
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
                // === CỘT CHÍNH (trái) - Chiếm 2/3 ===
                Group::make([
                    Section::make('Nội dung bài viết')
                        ->description('Viết tiêu đề và nội dung chi tiết cho bài viết')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            TextInput::make('title')
                                ->label('Tiêu đề bài viết')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn(string $operation, $state, $set) =>
                                $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                            TextInput::make('slug')
                                ->label('Đường dẫn URL')
                                ->prefix('https://yourdomain.com/posts/')
                                ->disabled()
                                ->dehydrated()
                                ->required()
                                ->unique(Post::class, 'slug', ignoreRecord: true)
                                ->helperText('Tự động tạo từ tiêu đề.'),

                            MarkdownEditor::make('content')
                                ->label('Nội dung bài viết')
                                ->required()
                                ->fileAttachmentsDisk('supabase')
                                ->fileAttachmentsDirectory('posts/content')
                                ->columnSpanFull(),
                        ]),
                ])->columnSpan(2),   // ← Quan trọng: Chiếm 2 cột

                // === CỘT PHỤ (phải) - Chiếm 1/3 ===
                Group::make([
                    Section::make('Ảnh bìa')
                        ->schema([
                            FileUpload::make('cover_image')
                                ->label('Ảnh bìa')
                                ->image()
                                ->disk('supabase')
                                ->directory('posts')
                                ->imageResizeMode('cover')
                                ->imageCropAspectRatio('16:9')
                                ->imagePreviewHeight('210')
                                ->imageEditor() // Hiện nút chỉnh sửa ảnh
                                ->imageEditorAspectRatios([
                                    '16:9',
                                ])
                                ->required()
                                ->helperText('Khuyến nghị tỷ lệ 16:9'),
                        ]),

                    Section::make('Tóm tắt')
                        ->schema([
                            Textarea::make('summary')
                                ->label('Mô tả ngắn')
                                ->rows(4)
                                ->maxlength(300)
                                ->required()
                                ->helperText('Tối đa 300 ký tự'),
                        ]),

                    Section::make('Trạng thái & Thống kê')
                        ->collapsible()
                        ->schema([
                            Toggle::make('is_published')
                                ->label('Công khai bài viết')
                                ->default(true)
                                ->live(),

                            DateTimePicker::make('published_at')
                                ->label('Ngày xuất bản')
                                ->default(now())
                                ->visible(fn(Get $get) => $get('is_published')),

                            TextInput::make('views')
                                ->label('Lượt xem ban đầu')
                                ->numeric()
                                ->default(0)
                                ->minValue(0),
                        ]),
                ])->columnSpan(1),   // ← Chiếm 1 cột
            ])
            ->columns([
                'sm' => 1,   // Mobile: xếp chồng
                'lg' => 3,   // Màn hình lớn: chia 3 cột
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
