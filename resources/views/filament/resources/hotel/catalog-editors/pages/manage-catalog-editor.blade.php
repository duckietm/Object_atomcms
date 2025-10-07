<x-filament::page>
    <div class="flex h-[80vh] gap-4">
        <!-- Left Sidebar -->
        <div class="w-72 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-y-auto p-3">
            <h2 class="font-semibold text-lg mb-2 text-gray-800 dark:text-gray-100">Catalog Pages</h2>

            @php
                $rootPages = \App\Models\Game\Furniture\CatalogPage::where('parent_id', -1)
                    ->orderBy('order_num')
                    ->get();
            @endphp

            @include('filament.resources.hotel.catalog-editors.pages.partials.catalog-tree', [
                'pages' => $rootPages,
                'depth' => 0,
                'selectedPage' => $selectedPage,
            ])
        </div>

        <!-- Right Content -->
        <div class="flex-1 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-y-auto p-3">
            <h2 class="font-semibold text-lg mb-2 text-gray-800 dark:text-gray-100">
                @if($selectedPage)
                    Items for: <span class="text-primary-600">{{ $selectedPage->caption }}</span>
                @else
                    Select a catalog page to view its items
                @endif
            </h2>

            <div>
                {{ $this->table }}
            </div>
        </div>
    </div>
</x-filament::page>
