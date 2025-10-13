<x-layouts.app :title="__('Employees')">

    <div 
        x-data="employeesComponent({{ $titles }})"
        x-on:attachment-updated.window="handleAttachmentUpdatedEvent"
        x-on:attachment-added.window="handleAttachmentAddedEvent"
        x-on:attachment-deleted.window="handleAttachmentDeletedEvent"
    >

        <!-- modals -->
        @include('modals.employee-form')
        @include('modals.attachment-form')

        <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

            <!-- header -->
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-semibold">{{ __('Employees') }} <span class="text-gray-500 text-xs" x-text="total"></span></h2>
                @if(auth()->user()->hasPermissionTo('create_employee'))
                    <flux:button variant="primary" @click="showCreateFormModal">
                    {{ __('Create Employee') }}
                    </flux:button>
                @endif
            </div>

            <!-- employees table -->
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
                                <div x-on:click="setSorting('cid')" class="flex items-center gap-1 cursor-pointer">
                                    <flux:icon.bars-arrow-down class="size-4" x-show="filters.sort === 'cid' && filters.sort_direction === 'desc'" />
                                    <flux:icon.bars-arrow-up class="size-4" x-show="filters.sort === 'cid' && filters.sort_direction === 'asc'" />
                                    <flux:icon.arrows-up-down class="size-4" x-show="filters.sort !== 'cid'" />
                                    <div>{{ __('Civil ID') }}</div>
                                </div>
                            </th>
                            <th>
                                <div x-on:click="setSorting('title_id')" class="flex items-center gap-1 cursor-pointer">
                                    <flux:icon.bars-arrow-down class="size-4" x-show="filters.sort === 'title_id' && filters.sort_direction === 'desc'" />
                                    <flux:icon.bars-arrow-up class="size-4" x-show="filters.sort === 'title_id' && filters.sort_direction === 'asc'" />
                                    <flux:icon.arrows-up-down class="size-4" x-show="filters.sort !== 'title_id'" />
                                    <div>{{ __('Title') }}</div>
                                </div>
                            </th>
                            <th>
                                <div x-on:click="setSorting('is_active')" class="flex items-center gap-1 cursor-pointer">
                                    <flux:icon.bars-arrow-down class="size-4" x-show="filters.sort === 'is_active' && filters.sort_direction === 'desc'" />
                                    <flux:icon.bars-arrow-up class="size-4" x-show="filters.sort === 'is_active' && filters.sort_direction === 'asc'" />
                                    <flux:icon.arrows-up-down class="size-4" x-show="filters.sort !== 'is_active'" />
                                    <div>{{ __('Status') }}</div>
                                </div>
                            </th>
                            @if(auth()->user()->hasPermissionTo('view_all_employee_attachments'))
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
                                <flux:input x-model="filters.cid" />
                            </th>
                            <th>
                                <flux:select x-model="filters.title_id" >
                                    <option value="" selected>{{ __('All') }}</option>
                                    <template x-for="title in titles" :key="title.id">
                                        <option x-bind:value="title.id" x-text="title.name"></option>
                                    </template>
                                </flux:select>
                            </th>
                            <th>
                                <flux:select x-model="filters.is_active" >
                                    <option value="" selected>{{ __('All') }}</option>
                                    <option value="true">{{ __('Active') }}</option>
                                    <option value="false">{{ __('Inactive') }}</option>
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
                        <template x-for="employee in employees" :key="employee.id">
                            <tr>
                                <td x-text="employee.name"></td>
                                <td x-text="employee.cid"></td>
                                <td x-text="employee.title?.name"></td>
                                <td x-text="employee.is_active ? '{{ __('Active') }}' : '{{ __('Inactive') }}'"></td>
                                <td>
                                    <template x-if="employee.can_view_attachment">
                                    <!-- attachments -->
                                        <div x-data="{attachable: employee, attachable_type: 'employee'}">
                                            @include('includes.attachments-list')
                                        </div>
                                    </template>
                                </td>
                                <td>
                                <div class="flex gap-2 justify-end">
                                    <template x-if="employee.can_update">
                                        <flux:icon.pencil-square class="size-4 text-blue-500 dark:text-blue-300" x-on:click="showEditFormModal(employee)" />
                                    </template>
                                    <template x-if="employee.can_delete">
                                        <flux:icon.trash class="size-4 text-red-500 dark:text-red-300" x-on:click="deleteEmployee(employee)" />
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