<?php

namespace App\Filament\Resources\Hotel\OpenPositions\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Hotel\OpenPositions\OpenPositionResource;
use Filament\Resources\Pages\ListRecords;

class ListOpenPositions extends ListRecords
{
    protected static string $resource = OpenPositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}