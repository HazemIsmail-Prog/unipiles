<!-- modal -->
<flux:modal name="form-modal" variant="flyout" x-on:close="form = getEmptyForm()">
    <form @submit.prevent="submitForm" class="space-y-6" x-bind:disabled="submitting">
        <template x-if="form.id">
            <flux:heading size="lg">{{__('Update Project')}}</flux:heading>
        </template>
        <template x-if="!form.id">
            <flux:heading size="lg">{{__('Create Project')}}</flux:heading>
        </template>
        <flux:input x-model="form.name_ar" label="{{__('Name (Arabic)')}}" />
        <flux:input x-model="form.name_en" label="{{__('Name (English)')}}" />
        <flux:select x-model="form.company_id" label="{{__('Company')}}" >
            <option value="" disabled selected>{{__('Select a company')}}</option>
            <template x-for="company in companies" :key="company.id">
                <option x-bind:value="company.id" x-text="company.name"></option>
            </template>
        </flux:select>
        <div class="flex">
            <flux:spacer />
            <flux:button type="submit" variant="primary">{{__('Save')}}</flux:button>
        </div>
    </form>
</flux:modal>