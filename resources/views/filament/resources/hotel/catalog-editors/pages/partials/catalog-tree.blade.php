<ul class="space-y-1 pl-{{ $depth * 4 }} text-sm">
    @foreach($pages as $page)
        @php
            $children = \App\Models\Game\Furniture\CatalogPage::where('parent_id', $page->id)
                ->orderBy('order_num')
                ->get();
            $hasChildren = $children->isNotEmpty();
        @endphp

        <li>
            <div class="flex items-center">
                @if($hasChildren)
                    <button
                        wire:click="toggleExpand({{ $page->id }})"
                        class="text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 mr-1 focus:outline-none"
                    >
                        @if($this->isExpanded($page->id))
                            ▼
                        @else
                            ▶
                        @endif
                    </button>
                @else
                    <span class="w-3 inline-block"></span>
                @endif

                <button
                    wire:click="selectPage({{ $page->id }})"
                    class="text-left w-full px-2 py-1 rounded hover:bg-gray-100 dark:hover:bg-gray-800
                        {{ $selectedPage && $selectedPage->id === $page->id ? 'bg-gray-200 dark:bg-gray-700 font-semibold' : '' }}">
                    {{ $page->caption }}
                </button>
            </div>

            @if($hasChildren && $this->isExpanded($page->id))
                @include('filament.resources.hotel.catalog-editors.pages.partials.catalog-tree', [
                    'pages' => $children,
                    'depth' => $depth + 1,
                    'selectedPage' => $selectedPage,
                ])
            @endif
        </li>
    @endforeach
</ul>
