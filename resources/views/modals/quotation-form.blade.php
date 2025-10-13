<!-- modal -->
<flux:modal name="form-modal" variant="flyout" x-on:close="form = getEmptyForm()">
    <form @submit.prevent="submitForm" class="space-y-6" x-bind:disabled="submitting">
        <template x-if="form.id">
            <div>
                <flux:heading size="lg">{{ __('Update Quotation') }}</flux:heading>
            </div>
        </template>
        <template x-if="!form.id">
            <flux:heading size="lg">{{ __('Create Quotation') }}</flux:heading>
        </template>
        <flux:input type="date" x-model="form.date" label="{{ __('Date') }}" />
        <flux:input x-model="form.ref" list="ref-datalist" label="{{ __('Ref') }}" />
        <datalist id="ref-datalist">
            <option value="UP/2025/Q/" />
        </datalist>
        <flux:textarea rows="auto" x-model="form.subject" label="{{ __('Subject') }}" />
        <flux:input x-model="form.project" label="{{ __('Project') }}" />
        <flux:input x-model="form.sent_to" label="{{ __('Sent To') }}" />
        <div class="flex">
            <flux:spacer />
            <flux:button type="submit" variant="primary">{{ __('Save') }}</flux:button>
        </div>
    </form>
</flux:modal>