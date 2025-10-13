<x-layouts.app :title="__('Users')">

    <div x-data="usersComponent({{ $roles }},{{ $permissions }})">

        <!-- modals -->
        @include('modals.user-form')

        <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

            <!-- header -->
            <div class="flex items-center justify-between">
                <div class="flex items-baseline gap-1">
                    <flux:heading size="xl">{{ __('Users') }}</flux:heading>
                    <flux:text class="text-[10px]" x-text="total"></flux:text>
                </div>
                @if(auth()->user()->hasPermissionTo('create_user'))
                    <flux:button variant="primary" @click="showCreateFormModal">
                        {{ __('Create User') }}
                    </flux:button>
                @endif
            </div>

            <!-- users table -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Permissions') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-if="users.length === 0">
                            <tr>
                                <td colspan="4" class="text-center text-gray-400 py-8">
                                    {{ __('No users found.') }}
                                </td>
                            </tr>
                        </template>
                        <template x-for="user in users" :key="user.id">
                            <tr>
                                <td x-text="user.name"></td>
                                <td x-text="user.email"></td>
                                <td>
                                    <template x-for="permission in user.all_permissions" :key="permission">
                                        <flux:text x-text="permission"></flux:text>
                                    </template>
                                </td>
                                <td>
                                    <div class="flex gap-2 justify-end items-center">
                                        <template x-if="user.can_update">
                                            <flux:icon.pencil-square class="size-4 text-blue-500 dark:text-blue-300" x-on:click="showEditFormModal(user)" />
                                        </template>
                                        <template x-if="user.can_delete">
                                            <flux:icon.trash class="size-4 text-red-500 dark:text-red-300" x-on:click="deleteUser(user)" x-bind:class="{ 'opacity-50 pointer-events-none': deleting === user.id }" />
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