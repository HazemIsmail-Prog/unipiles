<x-layouts.app :title="__('Documents')">

    <div 
        x-data="documentsComponent({{ $projects }}, {{ $project_id }})"
        x-on:attachment-updated.window="handleAttachmentUpdatedEvent"
        x-on:attachment-added.window="handleAttachmentAddedEvent"
        x-on:attachment-deleted.window="handleAttachmentDeletedEvent"
    >        
    
        <!-- modals -->
        @include('modals.document-form')
        @include('modals.attachment-form')

        <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

            <!-- header -->
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-semibold">{{ __('Documents') }} <span class="text-gray-500 text-xs" x-text="total"></span></h2>
                @if(auth()->user()->hasPermissionTo('create_document'))
                    <flux:button variant="primary" @click="showCreateFormModal">
                        {{ __('Create Document') }}
                    </flux:button>
                @endif
            </div>

            <!-- documents table -->
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
                                <div x-on:click="setSorting('type')" class="flex items-center gap-1 cursor-pointer">
                                    <flux:icon.bars-arrow-down class="size-4" x-show="filters.sort === 'type' && filters.sort_direction === 'desc'" />
                                    <flux:icon.bars-arrow-up class="size-4" x-show="filters.sort === 'type' && filters.sort_direction === 'asc'" />
                                    <flux:icon.arrows-up-down class="size-4" x-show="filters.sort !== 'type'" />
                                    <div>{{ __('Type') }}</div>
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
                                <div x-on:click="setSorting('sent_from')" class="flex items-center gap-1 cursor-pointer">
                                    <flux:icon.bars-arrow-down class="size-4" x-show="filters.sort === 'sent_from' && filters.sort_direction === 'desc'" />
                                    <flux:icon.bars-arrow-up class="size-4" x-show="filters.sort === 'sent_from' && filters.sort_direction === 'asc'" />
                                    <flux:icon.arrows-up-down class="size-4" x-show="filters.sort !== 'sent_from'" />
                                    <div>{{ __('From') }}</div>
                                </div>
                            </th>
                            <th>
                                <div x-on:click="setSorting('sent_to')" class="flex items-center gap-1 cursor-pointer">
                                    <flux:icon.bars-arrow-down class="size-4" x-show="filters.sort === 'sent_to' && filters.sort_direction === 'desc'" />
                                    <flux:icon.bars-arrow-up class="size-4" x-show="filters.sort === 'sent_to' && filters.sort_direction === 'asc'" />
                                    <flux:icon.arrows-up-down class="size-4" x-show="filters.sort !== 'sent_to'" />
                                    <div>{{ __('To') }}</div>
                                </div>
                            </th>
                            <th>
                                <div x-on:click="setSorting('project_id')" class="flex items-center gap-1 cursor-pointer">
                                    <flux:icon.bars-arrow-down class="size-4" x-show="filters.sort === 'project_id' && filters.sort_direction === 'desc'" />
                                    <flux:icon.bars-arrow-up class="size-4" x-show="filters.sort === 'project_id' && filters.sort_direction === 'asc'" />
                                    <flux:icon.arrows-up-down class="size-4" x-show="filters.sort !== 'project_id'" />
                                    <div>{{ __('Project') }}</div>
                                </div>
                            </th>
                            @if(auth()->user()->hasPermissionTo('view_all_document_attachments'))
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
                                <flux:input x-model="filters.type" />
                            </th>
                            <th>
                                <flux:input x-model="filters.ref" />
                            </th>
                            <th>
                                <flux:input x-model="filters.subject" />
                            </th>
                            <th>
                                <flux:input x-model="filters.sent_from" />
                            </th>
                            <th>
                                <flux:input x-model="filters.sent_to" />
                            </th>
                            <th>
                                <flux:select x-model="filters.project_id" >
                                    <option value="" selected>{{ __('All') }}</option>
                                    <template x-for="project in projects" :key="project.id">
                                        <option x-bind:selected="filters.project_id === project.id" x-bind:value="project.id" x-text="project.name"></option>
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
                        <template x-for="document in documents" :key="document.id">
                            <tr>
                                <td class="whitespace-nowrap" x-text="document.date"></td>
                                <td x-text="document.type"></td>
                                <td x-text="document.ref"></td>
                                <td x-text="document.subject"></td>
                                <td x-text="document.sent_from"></td>
                                <td x-text="document.sent_to"></td>
                                <td x-text="document.project.name"></td>
                                <td>
                                    <template x-if="document.can_view_attachment">
                                        <!-- attachments -->
                                        <div x-data="{attachable: document, attachable_type: 'document'}">
                                            @include('includes.attachments-list')
                                        </div>
                                    </template>
                                </td>
                                <td>
                                <div class="flex gap-2 justify-end">
                                    <template x-if="document.can_update">
                                        <flux:icon.pencil-square class="size-4 text-blue-500 dark:text-blue-300" x-on:click="showEditFormModal(document)" />
                                    </template>
                                    <template x-if="document.can_delete">
                                        <flux:icon.trash class="size-4 text-red-500 dark:text-red-300" x-on:click="deleteDocument(document)" />
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