<?php

namespace App\Filament\Resources\Albums\Schemas;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Support\LocaleOptions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
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
                        ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                    TextInput::make('slug')->required()->maxLength(255)->unique(ignoreRecord: true),
                    TinyEditor::make('description')->columnSpanFull(),
                    SpatieMediaLibraryFileUpload::make('cover')->collection('cover')->image(),
                    SpatieMediaLibraryFileUpload::make('files')->collection('files')->multiple()->reorderable()->image()->required(),
                    Toggle::make('is_published')->default(true),
                    TextInput::make('sort_order')->numeric()->default(0),
                ])->columns(2),
            ]);
    }
}
