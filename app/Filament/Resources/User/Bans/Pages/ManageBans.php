<?php

namespace App\Filament\Resources\User\Bans\Pages;

use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\User\Bans\BanResource;

class ManageBans extends ManageRecords
{
    protected static string $resource = BanResource::class;

    protected function getActions(): array
    {
        return [
            // ...
        ];
    }
}
