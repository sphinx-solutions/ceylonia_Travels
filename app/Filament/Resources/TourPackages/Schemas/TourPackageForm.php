<?php

namespace App\Filament\Resources\TourPackages\Schemas;

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
                        ->afterStateUpdated(function ($set, ?string $state) {
                            if ($state) {
                                $set('slug', Slug::make($state));
                                $set('meta_title', $state);
                            }
                        }),
                    TextInput::make('slug')->required()->maxLength(255)->unique(ignoreRecord: true)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Slug::make($state))),
                    TextInput::make('destination')->maxLength(255),
                    TextInput::make('duration_days')->numeric(),
                    TextInput::make('price_from')->numeric()->prefix('€'),
                    Select::make('currency')->options(['EUR' => 'EUR', 'USD' => 'USD', 'GBP' => 'GBP'])->default('EUR'),
                    Textarea::make('excerpt')->rows(2)->columnSpanFull()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($set, ?string $state) => $set(
                            'meta_description',
                            Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($state ?? '')) ?? ''), 150, '')
                        )),
                    TinyEditor::make('content')->columnSpanFull()
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($set, $get, ?string $state) {
                            if (! filled($get('meta_description'))) {
                                $set('meta_description', Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($state ?? '')) ?? ''), 150, ''));
                            }
                        }),
                    SpatieMediaLibraryFileUpload::make('cover')->collection('cover')->image()->required()
                        ->helperText('OG/Twitter use a 1200×630 JPG conversion at ~70% quality.'),
                    SpatieMediaLibraryFileUpload::make('gallery')->collection('gallery')->multiple()->reorderable()->image(),
                ])->columns(2),

                Section::make('Publishing & SEO')->schema([
                    Toggle::make('is_featured')->default(false),
                    Toggle::make('is_published')->default(true),
                    TextInput::make('sort_order')->numeric()->default(0),
                    TextInput::make('meta_title')->required()->maxLength(255),
                    Textarea::make('meta_description')->required()->rows(3)->maxLength(160),
                    Textarea::make('schema_data')
                        ->rows(6)
                        ->helperText('Optional raw JSON-LD override.')
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
