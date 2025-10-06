<?php

namespace App\Filament\Resources\Hotel\CommandLogs\Pages;

use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\Hotel\CommandLogs\CommandLogResource;

class ManageCommandLogs extends ManageRecords
{
    protected static string $resource = CommandLogResource::class;

    protected function getActions(): array
    {
        return [];
    }

    public function getPrimaryKey(): string
    {
        return 'timestamp';
    }
}
