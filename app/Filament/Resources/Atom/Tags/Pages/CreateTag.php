<?php

namespace App\Filament\Resources\Atom\Tags\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Atom\Tags\TagResource;

class CreateTag extends CreateRecord
{
    protected static string $resource = TagResource::class;
}
