<?php

namespace App\Filament\Resources\Pages\Schemas;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Support\LocaleOptions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->schema([
                    Select::make('locale')->options(LocaleOptions::forSelect())->default('en')->required(),
                    Select::make('slug')
                        ->options(['about' => 'About Us', 'contact' => 'Contact Us'])
                        ->required(),
                    TextInput::make('title')->required()->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($set, ?string $state) => $set('meta_title', $state)),
                    TinyEditor::make('content')->columnSpanFull()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($set, ?string $state) => $set(
                            'meta_description',
                            Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($state ?? '')) ?? ''), 150, '')
                        )),
                    TextInput::make('meta_title')->required()->maxLength(255),
                    Textarea::make('meta_description')->required()->rows(3)->maxLength(160),
                ])->columns(2),
            ]);
    }
}
