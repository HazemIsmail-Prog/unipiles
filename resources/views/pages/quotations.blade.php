<x-layouts.app :title="__('Quotations')">

    <div 
        x-data="quotationsComponent"
        x-on:attachment-updated.window="handleAttachmentUpdatedEvent"
        x-on:attachment-added.window="handleAttachmentAddedEvent"
        x-on:attachment-deleted.window="handleAttachmentDeletedEvent"
    >

        <!-- modals -->
        @include('modals.quotation-form')
        @include('modals.attachment-form')

        <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

            <!-- header -->
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-semibold">{{ __('Quotations') }} <span class="text-gray-500 text-xs" x-text="total"></span></h2>
                @if(auth()->user()->hasPermissionTo('create_quotation'))
                    <flux:button variant="primary" @click="showCreateFormModal">
                        {{ __('Create Quotation') }}
                    </flux:button>
                @endif
            </div>

            <!-- quotations table -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>
                                <div x-on:click="setSorting('date')" class="flex items-center gap-1 cursor-pointer">
                                    <flux:icon.bars-arrow-down class="size-4" x-show="filters.sort === 'date' && filters.sort_direction === 'desc'" />
                                    <flux:icon.bars-arrow-up class="size-4" x-show="filters.sort === 'date' && filters.sort_direction === 'asc'" />
                                    <flux:icon.arrows-up-down class="size-4" x-show="filters.sort !== 'date'" />
                                    <div>{{ __('Date') }}</div>
                                </div>
                            </th>
                            <th>
                                <div x-on:click="setSorting('ref')" class="flex items-center gap-1 cursor-pointer">
                                    <flux:icon.bars-arrow-down class="size-4" x-show="filters.sort === 'ref' && filters.sort_direction === 'desc'" />
                                    <flux:icon.bars-arrow-up class="size-4" x-show="filters.sort === 'ref' && filters.sort_direction === 'asc'" />
                                    <flux:icon.arrows-up-down class="size-4" x-show="filters.sort !== 'ref'" />
                                    <div>{{ __('Ref') }}</div>
                                </div>
                            </th>
                            <th>
                                <div x-on:click="setSorting('subject')" class="flex items-center gap-1 cursor-pointer">
                                    <flux:icon.bars-arrow-down class="size-4" x-show="filters.sort === 'subject' && filters.sort_direction === 'desc'" />
                                    <flux:icon.bars-arrow-up class="size-4" x-show="filters.sort === 'subject' && filters.sort_direction === 'asc'" />
                                    <flux:icon.arrows-up-down class="size-4" x-show="filters.sort !== 'subject'" />
                                    <div>{{ __('Subject') }}</div>
                                </div>
                            </th>
                            <th>
                                <div x-on:click="setSorting('project')" class="flex items-center gap-1 cursor-pointer">
                                    <flux:icon.bars-arrow-down class="size-4" x-show="filters.sort === 'project' && filters.sort_direction === 'desc'" />
                                    <flux:icon.bars-arrow-up class="size-4" x-show="filters.sort === 'project' && filters.sort_direction === 'asc'" />
                                    <flux:icon.arrows-up-down class="size-4" x-show="filters.sort !== 'project'" />
                                    <div>{{ __('Project') }}</div>
                                </div>
                            </th>
                            <th>
                                <div x-on:click="setSorting('sent_to')" class="flex items-center gap-1 cursor-pointer">
                                    <flux:icon.bars-arrow-down class="size-4" x-show="filters.sort === 'sent_to' && filters.sort_direction === 'desc'" />
                                    <flux:icon.bars-arrow-up class="size-4" x-show="filters.sort === 'sent_to' && filters.sort_direction === 'asc'" />
                                    <flux:icon.arrows-up-down class="size-4" x-show="filters.sort !== 'sent_to'" />
                                    <div>{{ __('Sent To') }}</div>
                                </div>
                            </th>
                            @if(auth()->user()->hasPermissionTo('view_all_quotation_attachments'))
                            <th>{{ __('Attachments') }}</th>
                            @else
                                <th></th>
                            @endif
                            <th></th>
                        </tr>
                        <tr>
                            <th>
                                <flux:input x-model="filters.date" type="date" />
                            </th>
                            <th>
                                <flux:input x-model="filters.ref" />
                            </th>
                            <th>
                                <flux:input x-model="filters.subject" />
                            </th>
                            <th>
                                <flux:input x-model="filters.project" />
                            </th>
                            <th>
                                <flux:input x-model="filters.sent_to" />
                            </th>
                            <th>
                                <flux:button variant="outline" @click="clearFilters">
                                    {{ __('Clear Filters') }}
                                </flux:button>
                            </th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-if="quotations.length === 0">
                            <tr>
                                <td colspan="8" class="text-center text-gray-400 py-8">
                                    {{ __('No quotations found.') }}
                                </td>
                            </tr>
                        </template>
                        <template x-for="quotation in quotations" :key="quotation.id">
                            <tr>
                                <td class="whitespace-nowrap" x-text="quotation.date"></td>
                                <td x-text="quotation.ref"></td>
                                <td x-text="quotation.subject"></td>
                                <td x-text="quotation.project"></td>
                                <td>
                                    <span x-show="quotation.sent_to" x-text="quotation.sent_to"></span>
                                </td>
                                <td>
                                    <template x-if="quotation.can_view_attachment">
                                        <div x-data="{attachable: quotation, attachable_type: 'quotation'}">
                                                @include('includes.attachments-list')
                                            </div>
                                        </div>
                                    </template>
                                </td>
                                <td>
                                    <div class="flex gap-2 justify-end items-center">
                                        <template x-if="quotation.can_update">
                                            <flux:icon.pencil-square class="size-4 text-blue-500 dark:text-blue-300" x-on:click="showEditFormModal(quotation)" />
                                        </template>
                                        <template x-if="quotation.can_delete">
                                            <flux:icon.trash class="size-4 text-red-500 dark:text-red-300" x-on:click="deleteQuotation(quotation)" x-bind:class="{ 'opacity-50 pointer-events-none': deleting === quotation.id }" />
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