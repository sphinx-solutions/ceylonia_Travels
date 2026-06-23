<?php

namespace App\Filament\Resources\TourPackages\Schemas;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Support\LocaleOptions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class TourPackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Package Details')->schema([
                    Select::make('locale')->options(LocaleOptions::forSelect())->default('en')->required(),
                    TextInput::make('title')->required()->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                    TextInput::make('slug')->required()->maxLength(255)->unique(ignoreRecord: true),
                    TextInput::make('destination')->maxLength(255),
                    TextInput::make('duration_days')->numeric(),
                    TextInput::make('price_from')->numeric()->prefix('€'),
                    Select::make('currency')->options(['EUR' => 'EUR', 'USD' => 'USD', 'GBP' => 'GBP'])->default('EUR'),
                    Textarea::make('excerpt')->rows(2)->columnSpanFull(),
                    TinyEditor::make('content')->columnSpanFull(),
                    SpatieMediaLibraryFileUpload::make('cover')->collection('cover')->image()->required(),
                    SpatieMediaLibraryFileUpload::make('gallery')->collection('gallery')->multiple()->reorderable()->image(),
                ])->columns(2),

                Section::make('Publishing & SEO')->schema([
                    Toggle::make('is_featured')->default(false),
                    Toggle::make('is_published')->default(true),
                    TextInput::make('sort_order')->numeric()->default(0),
                    TextInput::make('meta_title')->maxLength(255),
                    Textarea::make('meta_description')->rows(3),
                ])->columns(2),
            ]);
    }
}
