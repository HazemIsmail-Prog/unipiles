<div class="flex gap-2 justify-end items-center">
    <div class="flex-1 flex flex-col gap-2">
        <template x-for="attachment in attachable.attachments" :key="attachment.id">
            <div
                class="attachment-card"
                x-bind:class="{'expired': attachment.is_expired, 'notify': attachment.notify_now}"
            >
                <a :href="`/attachments/${attachment.encrypted_id}`" target="_blank">
                    <p x-text="attachment.description"></p>
                    <p class="text-[8px]" x-text="attachment.formatted_expires_at"></p>
                </a>
                <template x-if="attachable.can_update_attachment">
                    <flux:icon.pencil-square class="size-4 text-blue-500 dark:text-blue-300" x-on:click="$store.attachments.showEditFormModal(attachment)" />
                </template>
                <template x-if="attachable.can_delete_attachment">
                    <flux:icon.trash class="size-4 text-red-500 dark:text-red-300" x-on:click="$store.attachments.deleteAttachment(attachment)" />
                </template>
            </div>
        </template>
    </div>
    <template x-if="attachable.can_create_attachment">
        <flux:icon.plus class="size-4 text-green-500 dark:text-green-300" x-on:click="$store.attachments.showCreateFormModal(attachable_type, attachable.id)" />
    </template>


</div>