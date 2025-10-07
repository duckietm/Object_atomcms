<?php

namespace App\Filament\Resources\Hotel\CatalogEditors\Pages;

use App\Filament\Resources\Hotel\CatalogEditors\CatalogEditorResource;
use App\Models\Game\Furniture\CatalogItem;
use App\Models\Game\Furniture\CatalogPage;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

class ManageCatalogEditor extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = CatalogEditorResource::class;

    protected string $view = 'filament.resources.hotel.catalog-editors.pages.manage-catalog-editor';

    public ?CatalogPage $selectedPage = null;
    public array $expandedPages = []; // 🔹 Track expanded node IDs

    public function selectPage(int $pageId): void
    {
        $this->selectedPage = CatalogPage::find($pageId);
        $this->resetTable();
    }

    public function toggleExpand(int $pageId): void
    {
        if (in_array($pageId, $this->expandedPages)) {
            $this->expandedPages = array_diff($this->expandedPages, [$pageId]);
        } else {
            $this->expandedPages[] = $pageId;
        }
    }

    public function isExpanded(int $pageId): bool
    {
        return in_array($pageId, $this->expandedPages);
    }

    public function getTableQuery()
    {
        return $this->selectedPage
            ? CatalogItem::query()->where('page_id', $this->selectedPage->id)
            : CatalogItem::query()->whereRaw('1=0');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('id')->label('Item ID')->sortable(),
            Tables\Columns\TextColumn::make('catalog_name')->label('Name'),
            Tables\Columns\TextColumn::make('cost_credits')->label('Credits'),
            Tables\Columns\TextColumn::make('cost_points')->label('Points'),
            Tables\Columns\TextColumn::make('points_type')->label('Type'),
            Tables\Columns\TextColumn::make('amount')->label('Amount'),
            Tables\Columns\IconColumn::make('club_only')->boolean()->label('Club Only'),
        ];
    }
}
