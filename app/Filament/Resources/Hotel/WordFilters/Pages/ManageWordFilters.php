<?php

namespace App\Filament\Resources\Hotel\WordFilters\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Hotel\WordFilters\WordFilterResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageWordFilters extends ManageRecords
{
    protected static string $resource = WordFilterResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
