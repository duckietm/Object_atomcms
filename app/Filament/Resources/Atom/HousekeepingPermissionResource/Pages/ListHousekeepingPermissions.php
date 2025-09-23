<?php

namespace App\Filament\Resources\Atom\HousekeepingPermissionResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Atom\HousekeepingPermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHousekeepingPermissions extends ListRecords
{
    protected static string $resource = HousekeepingPermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
