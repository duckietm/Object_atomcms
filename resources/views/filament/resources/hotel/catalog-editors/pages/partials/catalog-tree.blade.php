<ul class="pl-{{ $depth * 4 }} text-sm">
    @foreach($pages as $page)
        @php
            /** @var \App\Models\Game\Furniture\CatalogPage $page */
            $children   = \App\Models\Game\Furniture\CatalogPage::where('parent_id', $page->id)
                            ->orderBy('order_num')
                            ->get();
            $hasChildren = $children->isNotEmpty();
            $iconUrl     = $this->buildCatalogIconUrl((int) $page->icon_image);
            $fallbackUrl = $this->buildCatalogIconUrl(1);
            $isSubmenu   = ($page->parent_id !== -1);
        @endphp

        <li class="flex items-center gap-1 min-w-0">
            @if($hasChildren)
                <x-filament::icon-button
                    :icon="$this->isExpanded($page->id) ? 'heroicon-s-chevron-down' : 'heroicon-s-chevron-right'"
                    wire:click="toggleExpand({{ $page->id }})"
                    label="{{ $this->isExpanded($page->id) ? 'Collapse' : 'Expand' }}"
                    tooltip="{{ $this->isExpanded($page->id) ? 'Collapse' : 'Expand' }}"
                    size="xs"
                    color="gray"
                    variant="ghost"
                    class="shrink-0 inline-flex"
                    style="display: inline-flex; vertical-align: middle;"
                />
            @else
                <span class="inline-flex h-5 w-5 shrink-0"></span>
            @endif

            <button
                x-data="{
                    over: false,
                    clickTimer: null,
                    clickDelay: 250, // ms; adjust if you want a faster/slower dblclick window
                    singleClick() {
                        // Delay a bit; if dblclick happens, we cancel this
                        clearTimeout(this.clickTimer);
                        this.clickTimer = setTimeout(() => {
                            $wire.selectPage({{ $page->id }});
                        }, this.clickDelay);
                    },
                    doubleClick() {
                        // Cancel the pending singleClick and open modal
                        clearTimeout(this.clickTimer);
                        $wire.openEditPage({{ $page->id }});
                    },
                }"
                @dragover.prevent="over = true"
                @dragleave.prevent="over = false"
                @drop.prevent="
                    over = false;
                    const payload = event.dataTransfer.getData('text/plain');
                    if (payload) { $wire.moveItemsToPage(payload, {{ $page->id }}) }
                "
                {{-- single click selects page; double click opens edit modal --}}
                @click.stop.prevent="singleClick()"
                @dblclick.stop.prevent="doubleClick()"
                class="flex-1 min-w-0 inline-flex items-center gap-0.5 px-2 py-1 rounded
                       hover:bg-gray-100 dark:hover:bg-gray-800 whitespace-nowrap
                       {{ $selectedPage && $selectedPage->id === $page->id ? 'bg-gray-200 dark:bg-gray-700 font-semibold' : '' }}"
                :class="over ? 'ring-2 ring-primary-500/50 bg-primary-50 dark:bg-primary-900/10' : ''"
                title="Click to select. Double-click to edit. Drop items here to move."
                style="display: inline-flex; vertical-align: middle;"
            >
                @if($isSubmenu)
                    <x-css-layout-grid-small
                        class="h-5 w-5 text-gray-400 dark:text-gray-500 shrink-0 inline-block"
                        style="display: inline-block; vertical-align: middle;"
                    />
                @else
                    <span class="inline-block h-5 w-5 shrink-0"></span>
                @endif

                <span class="inline-flex h-5 w-5 shrink-0 items-center justify-center">
                    <img
                        src="{{ $iconUrl }}"
                        alt=""
                        class="max-w-full max-h-full object-contain align-middle"
                        loading="lazy"
                        referrerpolicy="no-referrer"
                        onerror="this.onerror=null;this.src='{{ $fallbackUrl }}';"
                        style="image-rendering: pixelated; image-rendering: crisp-edges;"
                    />
                </span>

                <span class="truncate inline-block" style="display: inline-block; vertical-align: middle;">
                    {{ $page->caption }}
                </span>
            </button>

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