<?php

namespace App\Filament\Resources\Posts\Schemas;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Support\LocaleOptions;
use App\Support\Slug;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Post Content')
                    ->schema([
                        Select::make('locale')
                            ->options(LocaleOptions::forSelect())
                            ->default('en')
                            ->required(),
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($set, ?string $state) {
                                if ($state) {
                                    $set('slug', Slug::make($state));
                                    $set('meta_title', $state);
                                }
                            }),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Slug::make($state))),
                        TextInput::make('author')
                            ->label('Author / specialist name')
                            ->default(fn () => auth()->user()?->name)
                            ->required()
                            ->maxLength(255)
                            ->helperText('Use a real byline — never “Admin”.'),
                        TinyEditor::make('content')
                            ->required()
                            ->columnSpanFull()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, ?string $state) => $set(
                                'meta_description',
                                Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($state ?? '')) ?? ''), 150, '')
                            )),
                        SpatieMediaLibraryFileUpload::make('banner')
                            ->collection('banner')
                            ->image()
                            ->helperText('Social preview uses a 1200×630 JPG conversion (~70% quality).')
                            ->required(),
                        SpatieMediaLibraryFileUpload::make('media')
                            ->collection('media')
                            ->multiple()
                            ->reorderable()
                            ->image(),
                    ])->columns(2),

                Section::make('Publishing & SEO')
                    ->schema([
                        Select::make('status')
                            ->options(['draft' => 'Draft', 'published' => 'Published'])
                            ->default('draft')
                            ->required(),
                        DateTimePicker::make('published_at'),
                        Toggle::make('is_featured')->default(false),
                        TextInput::make('meta_title')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Auto-filled from the title; edit freely.'),
                        Textarea::make('meta_description')
                            ->required()
                            ->rows(3)
                            ->maxLength(160)
                            ->helperText('~150–160 characters. Auto-filled from content.'),
                        Textarea::make('schema_data')
                            ->rows(6)
                            ->helperText('Optional raw JSON-LD override. If filled, generated schema is skipped.')
                            ->columnSpanFull()
                            ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $state)
                            ->dehydrateStateUsing(function ($state) {
                                if (! filled($state)) {
                                    return null;
                                }
                                $decoded = json_decode($state, true);

                                return json_last_error() === JSON_ERROR_NONE ? $decoded : null;
                            }),
                    ])->columns(2),
            ]);
    }
}
