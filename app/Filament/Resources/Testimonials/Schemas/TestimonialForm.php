<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use App\Support\LocaleOptions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->schema([
                    Select::make('locale')->options(LocaleOptions::forSelect())->default('en')->required(),
                    TextInput::make('author_name')->required()->maxLength(255),
                    TextInput::make('author_country')->maxLength(255),
                    Textarea::make('content')->required()->rows(4),
                    Select::make('rating')->options([5 => '5', 4 => '4', 3 => '3', 2 => '2', 1 => '1'])->default(5)->required(),
                    SpatieMediaLibraryFileUpload::make('photo')->collection('photo')->image(),
                    Toggle::make('is_published')->default(true),
                    TextInput::make('sort_order')->numeric()->default(0),
                ])->columns(2),
            ]);
    }
}
