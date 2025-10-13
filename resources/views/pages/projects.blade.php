<x-layouts.app :title="__('Projects')">

    <div x-data="projectsComponent({{ $companies }})">

        <!-- modals -->
        @include('modals.project-form')

        <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

            <!-- header -->
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-semibold">{{ __('Projects') }} <span class="text-gray-500 text-xs" x-text="total"></span></h2>
                @if(auth()->user()->hasPermissionTo('create_project'))
                <flux:button variant="primary" @click="showCreateFormModal">
                        {{ __('Create Project') }}
                    </flux:button>
                @endif
            </div>

            <!-- projects table -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Company') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-if="projects.length === 0">
                            <tr>
                                <td colspan="3" class="text-center text-gray-400 py-8">
                                    {{ __('No projects found.') }}
                                </td>
                            </tr>
                        </template>
                        <template x-for="project in projects" :key="project.id">
                            <tr>
                                <td x-text="project.name"></td>
                                <td x-text="project.company.name"></td>
                                <td>
                                    <div class="flex gap-2 justify-end items-center">
                                        <template x-if="project.can_update">
                                            <flux:icon.pencil-square class="size-4 text-blue-500 dark:text-blue-300" x-on:click="showEditFormModal(project)" />
                                        </template>
                                        <template x-if="project.can_delete">
                                            <flux:icon.trash class="size-4 text-red-500 dark:text-red-300"
                                                x-on:click="deleteProject(project)"
                                                x-bind:class="{ 'opacity-50 pointer-events-none': deleting === project.id }"
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