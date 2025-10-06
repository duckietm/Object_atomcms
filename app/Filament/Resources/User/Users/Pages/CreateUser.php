<?php

namespace App\Filament\Resources\User\Users\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\User\Users\UserResource;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
