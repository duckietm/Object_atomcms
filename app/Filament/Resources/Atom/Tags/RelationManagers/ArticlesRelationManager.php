<?php

namespace App\Filament\Resources\Atom\Tags\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Actions\AttachAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Traits\TranslatableResource;
use App\Filament\Resources\Atom\Articles\ArticleResource;
use Filament\Resources\RelationManagers\RelationManager;

class ArticlesRelationManager extends RelationManager
{
    use TranslatableResource;

    // Use camelCase to match the method in the Tag model
    protected static string $relationship = 'websiteArticles';

    protected static ?string $recordTitleAttribute = 'title';

    public static string $translateIdentifier = 'article';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components(ArticleResource::getForm());
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns(ArticleResource::getTable())
            ->modifyQueryUsing(fn ($query) => $query->latest())
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect(),
            ])
            ->recordActions([
                ViewAction::make(),
                DetachAction::make(),
            ])
            ->toolbarActions([
                DetachBulkAction::make(),
            ]);
    }
}
