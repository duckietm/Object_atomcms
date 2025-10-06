<?php

namespace App\Filament\Resources\Atom\Articles\Pages;

use App\Models\User;
use App\Models\Article;
use App\Enums\NotificationType;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Atom\Articles\ArticleResource;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

    protected function afterCreate(): void
    {
        /** @var null|Article $articleCreated */
        $articleCreated = $this->getRecord();

        if (!$articleCreated || !$articleCreated->visible) return;

        $articleCreated->createFollowersNotification();
    }
}
