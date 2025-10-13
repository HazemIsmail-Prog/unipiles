<x-layouts.app :title="__('Permissions')">

    <div x-data="permissionsComponent">

        <!-- modals -->
        @include('modals.permission-form')

        <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

            <!-- header -->
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-semibold">{{__('Permissions')}} <span class="text-gray-500 text-xs" x-text="total"></span></h2>
                @if(auth()->user()->hasPermissionTo('create_permission'))
                    <flux:button variant="primary" @click="showCreateFormModal">
                        {{ __('Create Permission') }}
                    </flux:button>
                @endif
            </div>

            <!-- permissions table -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-if="permissions.length === 0">
                            <tr>
                                <td colspan="2" class="text-center text-gray-400 py-8">
                                    {{ __('No permissions found') }}
                                </td>
                            </tr>
                        </template>
                        <template x-for="permission in permissions" :key="permission.id">
                            <tr>
                                <td x-text="permission.name"></td>
                                <td x-text="permission.description"></td>
                                <td>
                                    <div class="flex gap-2 justify-end items-center">
                                        <template x-if="permission.can_update">
                                            <flux:icon.pencil-square class="size-4 text-blue-500 dark:text-blue-300" x-on:click="showEditFormModal(permission)" />
                                        </template>
                                        <template x-if="permission.can_delete">
                                            <flux:icon.trash class="size-4 text-red-500 dark:text-red-300"
                                                    x-on:click="deletePermission(permission)"
                                                    x-bind:class="{ 'opacity-50 pointer-events-none': deleting === permission.id }"
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