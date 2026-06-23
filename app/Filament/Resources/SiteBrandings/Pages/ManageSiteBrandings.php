<?php

namespace App\Filament\Resources\SiteBrandings\Pages;

use App\Filament\Resources\SiteBrandings\SiteBrandingResource;
use App\Models\SiteBranding;
use Filament\Resources\Pages\EditRecord;

class ManageSiteBrandings extends EditRecord
{
    protected static string $resource = SiteBrandingResource::class;

    protected static ?string $title = 'Logos & Branding';

    public function mount(int|string|null $record = null): void
    {
        $this->record = SiteBranding::instance();
        $this->authorizeAccess();
        $this->fillForm();
        $this->previousUrl = url()->previous();
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
