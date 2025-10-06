<?php

namespace App\Filament\Resources\Hotel\EmulatorSettings\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Hotel\EmulatorSettings\EmulatorSettingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmulatorSettings extends ListRecords
{
    protected static string $resource = EmulatorSettingResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
