<!-- modal -->
<flux:modal name="form-modal" variant="flyout" x-on:close="form = getEmptyForm()">
    <form @submit.prevent="submitForm" class="space-y-6" x-bind:disabled="submitting">
        <template x-if="form.id">
            <flux:heading size="lg">{{ __('Update Asset') }}</flux:heading>
        </template>
        <template x-if="!form.id">
            <flux:heading size="lg">{{ __('Create Asset') }}</flux:heading>
        </template>
        <flux:select x-model="form.asset_type_id" label="{{ __('Asset Type') }}" >
            <option value="" disabled selected>{{ __('Select a asset type') }}</option>
            <template x-for="asset_type in asset_types" :key="asset_type.id">
                <option x-bind:value="asset_type.id" x-text="asset_type.name"></option>
            </template>
        </flux:select>
        <flux:input x-model="form.name_ar" label="{{ __('Name (Arabic)') }}" />
        <flux:input x-model="form.name_en" label="{{ __('Name (English)') }}" />
        <flux:input x-model="form.serial" label="{{ __('Serial') }}" />
        <flux:input x-model="form.sub_category_name" label="{{ __('Sub Category Name') }}" />
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
        
        <div class="flex justify-end">
            <flux:button type="submit" variant="primary">{{ __('Save') }}</flux:button>
        </div>
    </form>
</flux:modal>