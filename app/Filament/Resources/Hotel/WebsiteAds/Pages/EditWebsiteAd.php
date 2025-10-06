<?php

namespace App\Filament\Resources\Hotel\WebsiteAds\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Hotel\WebsiteAds\WebsiteAdResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWebsiteAd extends EditRecord
{
    protected static string $resource = WebsiteAdResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}