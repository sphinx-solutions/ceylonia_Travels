<?php

namespace App\Filament\Resources\SiteBrandings;

use App\Filament\Resources\SiteBrandings\Pages\ManageSiteBrandings;
use App\Models\SiteBranding;
use BackedEnum;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SiteBrandingResource extends Resource
{
    protected static ?string $model = SiteBranding::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSwatch;

    protected static string|UnitEnum|null $navigationGroup = 'Site';

    protected static ?string $navigationLabel = 'Logos & Branding';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Site Logos')
                    ->description('Upload logos and favicon. Images are auto-converted to multiple sizes for SEO and performance.')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('logo')
                            ->collection('logo')
                            ->image()
                            ->helperText('Main header logo'),
                        SpatieMediaLibraryFileUpload::make('logo_dark')
                            ->collection('logo_dark')
                            ->image()
                            ->helperText('Optional dark variant'),
                        SpatieMediaLibraryFileUpload::make('footer_logo')
                            ->collection('footer_logo')
                            ->image()
                            ->helperText('Footer logo (falls back to main logo)'),
                        SpatieMediaLibraryFileUpload::make('favicon')
                            ->collection('favicon')
                            ->image()
                            ->helperText('Favicon & apple-touch-icon (32px and 180px generated automatically)'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSiteBrandings::route('/'),
        ];
    }
}
