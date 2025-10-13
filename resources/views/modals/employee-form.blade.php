<!-- modal -->
<flux:modal name="form-modal" variant="flyout" x-on:close="form = getEmptyForm()">
    <form @submit.prevent="submitForm" class="space-y-6" x-bind:disabled="submitting">
        <template x-if="form.id">
            <flux:heading size="lg">{{ __('Update Employee') }}</flux:heading>
        </template>
        <template x-if="!form.id">
            <flux:heading size="lg">{{ __('Create Employee') }}</flux:heading>
        </template>
        <flux:select x-model="form.title_id" label="{{ __('Title') }}" >
            <option value="" selected>{{ __('Select a title') }}</option>
            <option value="" disabled selected>{{ __('Select a title') }}</option>
            <template x-for="title in titles" :key="title.id">
                <option x-bind:value="title.id" x-text="title.name"></option>
            </template>
        </flux:select>
        <flux:input x-model="form.name_ar" label="{{ __('Name (Arabic)') }}" />
        <flux:input x-model="form.name_en" label="{{ __('Name (English)') }}" />
        <flux:input x-model="form.degree" label="{{ __('Degree') }}" />
        <flux:input x-model="form.cid" label="{{ __('Civil ID') }}" />
        <flux:input x-model="form.actual_salary" label="{{ __('Actual Salary') }}" />
        <flux:input x-model="form.ezn_salary" label="{{ __('EZN Salary') }}" />
        <flux:input x-model="form.employment_date" label="{{ __('Employment Date') }}" type="date" />
        <flux:input x-model="form.residency" label="{{ __('Residency') }}" />
        <flux:field variant="inline">
        <flux:switch
        x-bind:checked="form.is_active"
        x-bind:value="form.is_active"
        x-on:change="(event) => { 
            if(event.target.checked) 
            { 
                form.is_active = true; 
            } else { 
                form.is_active = false; 
            }
        }" />
        <flux:label>{{ __('Active') }}</flux:label>
    </flux:field>
        <div class="flex">
            <flux:spacer />
            <flux:button type="submit" variant="primary">{{ __('Save') }}</flux:button>
        </div>
    </form>
</flux:modal>