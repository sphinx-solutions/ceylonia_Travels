<?php

namespace App\Filament\Resources\Albums\Schemas;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Support\LocaleOptions;
use App\Support\Slug;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class AlbumForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->schema([
                    Select::make('locale')->options(LocaleOptions::forSelect())->default('en')->required(),
                    TextInput::make('name')->required()->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($set, ?string $state) {
                            if ($state) {
                                $set('slug', Slug::make($state));
                                $set('meta_title', $state);
                            }
                        }),
                    TextInput::make('slug')->required()->maxLength(255)->unique(ignoreRecord: true)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Slug::make($state))),
                    TinyEditor::make('description')->columnSpanFull()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($set, ?string $state) => $set(
                            'meta_description',
                            Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($state ?? '')) ?? ''), 150, '')
                        )),
                    SpatieMediaLibraryFileUpload::make('cover')->collection('cover')->image(),
                    SpatieMediaLibraryFileUpload::make('files')->collection('files')->multiple()->reorderable()->image()->required(),
                    Toggle::make('is_published')->default(true),
                    TextInput::make('sort_order')->numeric()->default(0),
                    TextInput::make('meta_title')->required()->maxLength(255),
                    Textarea::make('meta_description')->required()->rows(3)->maxLength(160),
                ])->columns(2),
            ]);
    }
}
