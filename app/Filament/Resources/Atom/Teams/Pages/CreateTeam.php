<?php

namespace App\Filament\Resources\Atom\Teams\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Atom\Teams\TeamResource;

class CreateTeam extends CreateRecord
{
    protected static string $resource = TeamResource::class;
}
