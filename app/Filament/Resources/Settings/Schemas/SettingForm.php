<?php

namespace App\Filament\Resources\Settings\Schemas;

use App\Support\LocaleOptions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->schema([
                    Select::make('locale')->options(LocaleOptions::forSelect())->default('en')->required(),
                    Select::make('key')
                        ->options([
                            'site_name' => 'Site Name (overrides env if set)',
                            'site_description' => 'Site Description',
                            'contact_intro' => 'Contact Page Intro',
                            'facebook' => 'Facebook URL',
                            'instagram' => 'Instagram URL',
                            'youtube' => 'YouTube URL',
                            'phone' => 'Phone Number',
                            'email' => 'Contact Email',
                            'address' => 'Address',
                        ])
                        ->searchable()
                        ->required()
                        ->helperText('Site name can also be set via SITE_NAME in .env'),
                    Textarea::make('value')->rows(4)->columnSpanFull(),
                ])->columns(2),
            ]);
    }
}
