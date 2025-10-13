<!-- modal -->
<flux:modal name="form-modal" variant="flyout" x-on:close="form = getEmptyForm()">
    <form @submit.prevent="submitForm" class="space-y-6" x-bind:disabled="submitting">
        <template x-if="form.id">
            <flux:heading size="lg">{{__('Update Permission')}}</flux:heading>
        </template>
        <template x-if="!form.id">
            <flux:heading size="lg">{{__('Create Permission')}}</flux:heading>
        </template>
        <flux:input x-model="form.name" label="{{__('Name')}}" />
        <flux:input x-model="form.description_en" label="{{__('Description (English)')}}" />
        <flux:input x-model="form.description_ar" label="{{__('Description (Arabic)')}}" />
        <div class="flex">
            <flux:spacer />
            <flux:button type="submit" variant="primary">{{__('Save')}}</flux:button>
        </div>
    </form>
</flux:modal>