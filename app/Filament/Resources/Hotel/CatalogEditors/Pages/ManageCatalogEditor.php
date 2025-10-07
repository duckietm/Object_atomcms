<?php

namespace App\Filament\Resources\Hotel\CatalogEditors\Pages;

use App\Filament\Resources\Hotel\CatalogEditors\CatalogEditorResource;
use App\Models\Game\Furniture\CatalogItem;
use App\Models\Game\Furniture\CatalogPage;
use App\Models\Miscellaneous\WebsiteSetting;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\DB;

class ManageCatalogEditor extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = CatalogEditorResource::class;
    protected string $view = 'filament.resources.hotel.catalog-editors.pages.manage-catalog-editor';

    public ?CatalogPage $selectedPage = null;
    public array $expandedPages = [];
    public array $selectedItemIds = [];

    public function selectPage(int $pageId): void
    {
        $this->selectedPage = CatalogPage::find($pageId);
        $this->selectedItemIds = [];
        $this->resetTable();
    }

    public function toggleExpand(int $pageId): void
    {
        if (in_array($pageId, $this->expandedPages, true)) {
            $this->expandedPages = array_values(array_diff($this->expandedPages, [$pageId]));
        } else {
            $this->expandedPages[] = $pageId;
        }
    }

    public function isExpanded(int $pageId): bool
    {
        return in_array($pageId, $this->expandedPages, true);
    }

    public function toggleSelectItem(int $itemId, bool $ctrl = false): void
    {
        if ($ctrl) {
            if (in_array($itemId, $this->selectedItemIds, true)) {
                $this->selectedItemIds = array_values(array_diff($this->selectedItemIds, [$itemId]));
            } else {
                $this->selectedItemIds[] = $itemId;
            }
        } else {
            $this->selectedItemIds = [$itemId];
        }

        $this->resetTable();
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
            Tables\Columns\ViewColumn::make('select_item')
                ->label('')
                ->view('filament.tables.columns.catalog-item-select')
                ->viewData([
                    'itemId'     => fn($record) => $record->id,
                    'isSelected' => fn($record) => in_array($record->id, $this->selectedItemIds, true),
                ])
                ->width('36px')
                ->sortable(false)
                ->searchable(false),

            Tables\Columns\ViewColumn::make('item_display')
                ->label('Item')
                ->view('filament.tables.columns.catalog-item-draggable')
                ->viewData([
                    'icon'       => fn($record) => $this->buildFurniIconUrl($record->catalog_name),
                    'name'       => fn($record) => $record->catalog_name,
                    'itemId'     => fn($record) => $record->id,
                    'isSelected' => fn($record) => in_array($record->id, $this->selectedItemIds, true),
                ])
                ->sortable(false)
                ->searchable(false),

            Tables\Columns\TextColumn::make('cost_credits')->label('Credits'),
            Tables\Columns\TextColumn::make('cost_points')->label('Points'),
            Tables\Columns\TextColumn::make('points_type')->label('Type'),
            Tables\Columns\TextColumn::make('amount')->label('Amount'),
            Tables\Columns\IconColumn::make('club_only')->boolean()->label('Club Only'),
        ];
    }

    public function moveItemToPage(int $itemId, int $targetPageId): void
    {
        $this->moveItemsToPage((string) $itemId, $targetPageId);
    }

    public function moveItemsToPage(string $itemIdsCsv, int $targetPageId): void
    {
        $ids = collect(explode(',', $itemIdsCsv))
            ->map(fn($v) => (int) trim($v))
            ->filter(fn($v) => $v > 0)
            ->unique()
            ->values()
            ->all();

        $target = CatalogPage::find($targetPageId);

        if (empty($ids) || !$target) {
            Notification::make()
                ->title('Move failed')
                ->body('No items selected or target page not found.')
                ->danger()
                ->send();
            return;
        }

        DB::transaction(function () use ($ids, $targetPageId) {
            $maxOrder = (int) (CatalogItem::where('page_id', $targetPageId)->max('order_number') ?? 0);
            foreach ($ids as $i => $id) {
                CatalogItem::whereKey($id)->update([
                    'page_id' => $targetPageId,
                    'order_number' => $maxOrder + 1 + $i,
                ]);
            }
        });

        $this->resetTable();
        $this->selectedItemIds = [];

        Notification::make()
            ->title('Items moved')
            ->body('Moved ' . count($ids) . ' item(s) to: ' . ($target->caption ?? ('#' . $targetPageId)))
            ->success()
            ->send();
    }

    protected function buildFurniIconUrl(string $catalogName): string
    {
        $base = $this->getFurniIconBasePath();
        $safeName = str_replace('*', '_', $catalogName);
        $path = rtrim($base, '/') . '/' . $safeName . '_icon.png';

        if (preg_match('#^(https?:)?//#', $path)) {
            return $path;
        }

        return asset($path);
    }

    protected function getFurniIconBasePath(): string
    {
        $setting = WebsiteSetting::where('key', 'furniture_icons_path')->first();
        return $setting && $setting->value ? rtrim($setting->value, '/') : '/images/furniture';
    }

    protected function getCatalogIconBasePath(): string
    {
        $setting = WebsiteSetting::where('key', 'catalog_icons_path')->first();
        return $setting && $setting->value ? rtrim($setting->value, '/') : '/gamedata/c_images/catalogue';
    }

    protected function buildCatalogIconUrl(int $iconImage): string
    {
        $iconImage = $iconImage > 0 ? $iconImage : 1;
        $base = $this->getCatalogIconBasePath();
        $path = $base . '/icon_' . $iconImage . '.png';

        if (preg_match('#^(https?:)?//#', $path)) {
            return $path;
        }

        return asset($path);
    }
}
