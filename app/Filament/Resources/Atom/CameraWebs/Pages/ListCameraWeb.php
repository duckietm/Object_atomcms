<?php

namespace App\Filament\Resources\Atom\CameraWebs\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Atom\CameraWebs\CameraWebResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCameraWeb extends ListRecords
{
    protected static string $resource = CameraWebResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
