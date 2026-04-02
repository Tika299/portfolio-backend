<?php

namespace App\Filament\Resources\Projects;

// Forms
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\MarkdownEditor;
// Tables
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
// Resources
use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Filament\Resources\Projects\Pages\EditProject;
use App\Filament\Resources\Projects\Pages\ListProjects;
use App\Filament\Resources\Projects\Pages\ViewProject;
use App\Filament\Resources\Projects\Schemas\ProjectForm;
use App\Filament\Resources\Projects\Schemas\ProjectInfolist;
use App\Filament\Resources\Projects\Tables\ProjectsTable;
// Others
use App\Models\Project;
use BackedEnum;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;
use Filament\Tables\Table;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(string $operation, $state, $set) =>
                    $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                TextInput::make('slug')
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique(Project::class, 'slug', ignoreRecord: true),

                Select::make('technologies')
                    ->multiple() // Chọn nhiều công nghệ cho 1 dự án
                    ->relationship('technologies', 'name')
                    ->preload(),

                FileUpload::make('thumbnail')
                    ->image()
                    ->maxSize(4096) // Giới hạn 1MB
                    ->imageResizeTargetWidth('1200') // Tự động resize chiều rộng về 1200px
                    ->imageResizeTargetHeight('675')
                    ->imageEditor() // Hiện nút chỉnh sửa ảnh
                    ->imageEditorAspectRatios([
                        '16:9',
                        '4:3',
                        '1:1',
                    ])
                    ->disk('public')
                    ->directory('portfolio/projects')
                    ->afterStateHydrated(fn($state, $set) => $state)
                    ->dehydrateStateUsing(function ($state) {
                        // Nếu là string (link cũ), giữ nguyên
                        if (is_string($state)) {
                            return $state;
                        }

                        // Nếu là file mới upload
                        if ($state instanceof \Illuminate\Http\UploadedFile) {
                            // Sử dụng Facade để upload, giúp VS Code nhận diện được method 'upload'
                            $result = Cloudinary::upload($state->getRealPath(), [
                                'folder' => 'portfolio/projects'
                            ]);

                            return $result->getSecurePath(); // Trả về link https://...
                        }

                        return $state;
                    })
                    ->formatStateUsing(fn($state) => $state),

                MarkdownEditor::make('content')
                    ->columnSpanFull(),


                TextInput::make('demo_url')->url(),
                TextInput::make('github_url')->url(),

                Toggle::make('status')
                    ->label('Hiển thị dự án này?')
                    ->default(true),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProjectInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')
                    ->circular(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('status')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                // Bạn có thể thêm bộ lọc theo trạng thái ở đây
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
            'index' => ListProjects::route('/'),
            'create' => CreateProject::route('/create'),
            'view' => ViewProject::route('/{record}'),
            'edit' => EditProject::route('/{record}/edit'),
        ];
    }
}
