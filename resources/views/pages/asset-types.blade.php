<x-layouts.app :title="__('Asset Types')">

    <div x-data="assetTypesComponent">

        <!-- modals -->
        @include('modals.asset-type-form')

        <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

            <!-- header -->
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-semibold">{{ __('Asset Types') }} <span class="text-gray-500 text-xs" x-text="total"></span></h2>
                @if(auth()->user()->hasPermissionTo('create_assettype'))
                    <flux:button variant="primary" @click="showCreateFormModal">
                        {{ __('Create Asset Type') }}
                    </flux:button>
                @endif
            </div>

            <!-- asset types table -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-if="assetTypes.length === 0">
                            <tr>
                                <td colspan="2" class="text-center text-gray-400 py-8">
                                    {{ __('No asset types found.') }}
                                </td>
                            </tr>
                        </template>
                        <template x-for="assetType in assetTypes" :key="assetType.id">
                            <tr>
                                <td x-text="assetType.name"></td>
                                <td>
                                    <div class="flex gap-2 justify-end items-center">
                                        <template x-if="assetType.can_update">
                                            <flux:icon.pencil-square class="size-4 text-blue-500 dark:text-blue-300" x-on:click="showEditFormModal(assetType)" />
                                        </template>
                                        <template x-if="assetType.can_delete">
                                            <flux:icon.trash class="size-4 text-red-500 dark:text-red-300"
                                                x-on:click="deleteAssetType(assetType)"
                                                x-bind:class="{ 'opacity-50 pointer-events-none': deleting === assetType.id }"
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