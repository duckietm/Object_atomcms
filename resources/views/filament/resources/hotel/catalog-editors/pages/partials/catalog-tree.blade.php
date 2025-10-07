<ul class="space-y-1 pl-{{ $depth * 4 }} text-sm">
    @foreach($pages as $page)
        @php
            $children = \App\Models\Game\Furniture\CatalogPage::where('parent_id', $page->id)
                ->orderBy('order_num')
                ->get();
            $hasChildren = $children->isNotEmpty();

            $iconUrl = $this->buildCatalogIconUrl((int) $page->icon_image);
            $fallbackUrl = $this->buildCatalogIconUrl(1);
        @endphp

        <li>
            <div class="flex items-center">
                @if($hasChildren)
                    <button
                        wire:click="toggleExpand({{ $page->id }})"
                        class="mr-1 text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none"
                        aria-label="{{ $this->isExpanded($page->id) ? 'Collapse' : 'Expand' }}"
                        title="{{ $this->isExpanded($page->id) ? 'Collapse' : 'Expand' }}"
                        style="display:inline-flex;align-items:center"
                    >
                        <span style="line-height:1">
                            @if($this->isExpanded($page->id)) ▼ @else ▶ @endif
                        </span>
                    </button>
                @else
                    <span class="w-3 inline-block"></span>
                @endif

                <button
                    x-data="{ over: false }"
                    @dragover.prevent="over = true"
                    @dragleave.prevent="over = false"
                    @drop.prevent="
						over = false;
						const payload = event.dataTransfer.getData('text/plain');
						if (payload) { $wire.moveItemsToPage(payload, {{ $page->id }}) }
					"
                    wire:click="selectPage({{ $page->id }})"
                    class="flex-1 px-2 py-1 rounded hover:bg-gray-100 dark:hover:bg-gray-800
                        {{ $selectedPage && $selectedPage->id === $page->id ? 'bg-gray-200 dark:bg-gray-700 font-semibold' : '' }}"
                    :class="over ? 'ring-2 ring-primary-500/50 bg-primary-50 dark:bg-primary-900/10' : ''"
                    style="display:inline-flex;align-items:center;gap:.5rem;min-width:0"
                    title="Drop item here to move it to this page"
                >
                    <img
                        src="{{ $iconUrl }}"
                        alt=""
                        class="h-5 w-5 shrink-0"
                        style="display:inline-block;vertical-align:middle"
                        loading="lazy"
                        referrerpolicy="no-referrer"
                        onerror="this.onerror=null;this.src='{{ $fallbackUrl }}';"
                    />
                    <span class="truncate" style="vertical-align:middle">{{ $page->caption }}</span>
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
