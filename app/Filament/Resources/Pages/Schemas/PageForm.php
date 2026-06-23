<?php

namespace App\Filament\Resources\Pages\Schemas;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Support\LocaleOptions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

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
                    TextInput::make('title')->required()->maxLength(255),
                    TinyEditor::make('content')->columnSpanFull(),
                    TextInput::make('meta_title')->maxLength(255),
                    Textarea::make('meta_description')->rows(3),
                ])->columns(2),
            ]);
    }
}
