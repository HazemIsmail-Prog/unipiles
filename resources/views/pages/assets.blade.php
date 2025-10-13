<x-layouts.app :title="__('Assets')">

    <div 
        x-data="assetsComponent({{ $asset_types }})"
        x-on:attachment-updated.window="handleAttachmentUpdatedEvent"
        x-on:attachment-added.window="handleAttachmentAddedEvent"
        x-on:attachment-deleted.window="handleAttachmentDeletedEvent"
    >

        <!-- modals -->
        @include('modals.asset-form')
        @include('modals.attachment-form')

        <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

            <!-- header -->
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-semibold">{{ __('Assets') }} <span class="text-gray-500 text-xs" x-text="total"></span></h2>
                @if(auth()->user()->hasPermissionTo('create_asset'))
                    <flux:button variant="primary" @click="showCreateFormModal">
                        {{ __('Create Asset') }}
                    </flux:button>
                @endif
            </div>

            <!-- assets table -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>
                                <div x-on:click="setSorting('{{ app()->getLocale() == 'ar' ? 'name_ar' : 'name_en' }}')" class="flex items-center gap-1 cursor-pointer">
                                    <flux:icon.bars-arrow-down class="size-4" x-show="filters.sort === '{{ app()->getLocale() == 'ar' ? 'name_ar' : 'name_en' }}' && filters.sort_direction === 'desc'" />
                                    <flux:icon.bars-arrow-up class="size-4" x-show="filters.sort === '{{ app()->getLocale() == 'ar' ? 'name_ar' : 'name_en' }}' && filters.sort_direction === 'asc'" />
                                    <flux:icon.arrows-up-down class="size-4" x-show="filters.sort !== '{{ app()->getLocale() == 'ar' ? 'name_ar' : 'name_en' }}'" />
                                    <div>{{ __('Name') }}</div>
                                </div>
                            </th>
                            <th>
                                <div x-on:click="setSorting('serial')" class="flex items-center gap-1 cursor-pointer">
                                    <flux:icon.bars-arrow-down class="size-4" x-show="filters.sort === 'serial' && filters.sort_direction === 'desc'" />
                                    <flux:icon.bars-arrow-up class="size-4" x-show="filters.sort === 'serial' && filters.sort_direction === 'asc'" />
                                    <flux:icon.arrows-up-down class="size-4" x-show="filters.sort !== 'serial'" />
                                    <div>{{ __('Serial') }}</div>
                                </div>
                            </th>
                            <th>
                                <div x-on:click="setSorting('sub_category_name')" class="flex items-center gap-1 cursor-pointer">                                    <flux:icon.bars-arrow-down class="size-4" x-show="filters.sort === 'sub_category_name' && filters.sort_direction === 'desc'" />
                                    <flux:icon.bars-arrow-up class="size-4" x-show="filters.sort === 'sub_category_name' && filters.sort_direction === 'asc'" />
                                    <flux:icon.arrows-up-down class="size-4" x-show="filters.sort !== 'sub_category_name'" />
                                    <div>{{ __('Sub Category Name') }}</div>
                                </div>
                            </th>
                            <th>
                                <div x-on:click="setSorting('asset_type_id')" class="flex items-center gap-1 cursor-pointer">
                                    <flux:icon.bars-arrow-down class="size-4" x-show="filters.sort === 'asset_type_id' && filters.sort_direction === 'desc'" />
                                    <flux:icon.bars-arrow-up class="size-4" x-show="filters.sort === 'asset_type_id' && filters.sort_direction === 'asc'" />
                                    <flux:icon.arrows-up-down class="size-4" x-show="filters.sort !== 'asset_type_id'" />
                                    <div>{{ __('Asset Type') }}</div>
                                </div>
                            </th>
                            @if(auth()->user()->hasPermissionTo('view_all_asset_attachments'))
                                <th>{{ __('Attachments') }}</th>
                            @else
                                <th></th>
                            @endif
                            <th></th>
                        </tr>
                        <tr>
                            <th>
                                <flux:input x-model="filters.name" />
                            </th>
                            <th>
                                <flux:input x-model="filters.serial" />
                            </th>
                            <th>
                                <flux:input x-model="filters.sub_category_name" />
                            </th>
                            <th>
                                <flux:select x-model="filters.asset_type_id" >
                                    <option value="" selected>{{ __('All') }}</option>
                                    <template x-for="asset_type in asset_types" :key="asset_type.id">
                                        <option x-bind:value="asset_type.id" x-text="asset_type.name"></option>
                                    </template>
                                </flux:select>
                            </th>
                            <th>
                                <flux:button variant="outline" @click="clearFilters">
                                    {{ __('Clear Filters') }}
                                </flux:button>
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-if="assets.length === 0">
                            <tr>
                                <td colspan="6" class="text-center text-gray-400 py-8">
                                    {{ __('No assets found') }}
                                </td>
                            </tr>
                        </template>
                        <template x-for="asset in assets" :key="asset.id">
                            <tr>
                                <td x-text="asset.name"></td>
                                <td x-text="asset.serial"></td>
                                <td x-text="asset.sub_category_name"></td>
                                <td x-text="asset.asset_type.name"></td>
                                <td>
                                    <template x-if="asset.can_view_attachment">
                                        <div x-data="{attachable: asset, attachable_type: 'asset'}">
                                            @include('includes.attachments-list')
                                        </div>
                                    </template>
                                </td>
                                <td>
                                    <div class="flex gap-2 justify-end items-center">
                                        <template x-if="asset.can_update">
                                            <flux:icon.pencil-square class="size-4 text-blue-500 dark:text-blue-300" x-on:click="showEditFormModal(asset)" />
                                        </template>
                                        <template x-if="asset.can_delete">
                                            <flux:icon.trash 
                                            class="size-4 text-red-500 dark:text-red-300" 
                                            x-on:click="deleteAsset(asset)" 
                                                x-bind:class="{ 'opacity-50 pointer-events-none': deleting === asset.id }" 
                                            />
                                        </template>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- load more button -->
            <div class="flex justify-center mt-4">
                <flux:button
                    variant="outline"
                    @click="loadMore"
                    x-bind:disabled="current_page === last_page"
                    x-show="current_page < last_page"
                >
                    {{ __('Load More') }}
                </flux:button>
            </div>

        </div>

    </div>

</x-layouts.app>