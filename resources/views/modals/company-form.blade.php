<!-- modal -->
<flux:modal name="form-modal" variant="flyout" x-on:close="form = getEmptyForm()">
    <form @submit.prevent="submitForm" class="space-y-6" x-bind:disabled="submitting">
        <template x-if="form.id">
            <flux:heading size="lg">{{__('Update Company')}}</flux:heading>
        </template>
        <template x-if="!form.id">
            <flux:heading size="lg">{{__('Create Company')}}</flux:heading>
        </template>
        <flux:input x-model="form.name_ar" label="{{__('Name (Arabic)')}}" />
        <flux:input x-model="form.name_en" label="{{__('Name (English)')}}" />
        <div class="flex">
            <flux:spacer />
            <flux:button type="submit" variant="primary">{{__('Save')}}</flux:button>
        </div>
    </form>
</flux:modal>