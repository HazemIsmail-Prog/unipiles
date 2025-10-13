<!-- modal -->
<flux:modal name="form-modal" variant="flyout" x-on:close="form = getEmptyForm()">
    <form @submit.prevent="submitForm" class="space-y-6" x-bind:disabled="submitting">
        <template x-if="form.id">
            <flux:heading size="lg">{{ __('Update User') }}</flux:heading>
        </template>
        <template x-if="!form.id">
            <flux:heading size="lg">{{ __('Create User') }}</flux:heading>
        </template>
        <flux:input x-model="form.name" label="{{ __('Name') }}" />
        <flux:input x-model="form.email" label="{{ __('Email') }}" type="email" />
        <flux:input x-model="form.password" label="{{ __('Password') }}" type="password" />

        <flux:fieldset>
            <flux:legend>{{ __('Roles') }}</flux:legend>
            <div class="space-y-3">
                <template x-for="role in roles" :key="role.id">
                    <flux:field variant="inline">
                        <flux:switch
                        x-bind:checked="form.roles?.includes(role.id)"
                        x-bind:value="role.id"
                        x-on:change="(event) => { 
                            if(event.target.checked) 
                            { 
                                form.roles.push(role.id); 
                            } else { 
                                form.roles = form.roles.filter(item => item !== role.id); 
                            }
                        }" />
                        <flux:label x-text="role.name"></flux:label>
                    </flux:field>
                </template>
            </div>
        </flux:fieldset>

        <flux:fieldset>
            <flux:legend>{{ __('Permissions') }}</flux:legend>
            <div class="space-y-3">
                <template x-for="permission in permissions" :key="permission.id">
                    <flux:field variant="inline">
                        <flux:switch
                        x-bind:checked="form.permissions?.includes(permission.id)"
                        x-bind:value="permission.id"
                        x-on:change="(event) => { 
                            if(event.target.checked) 
                            { 
                                form.permissions.push(permission.id); 
                            } else { 
                                form.permissions = form.permissions.filter(item => item !== permission.id); 
                            }
                        }" />
                        <flux:label x-text="permission.description"></flux:label>
                    </flux:field>
                </template>
            </div>
        </flux:fieldset>


        <div class="flex">
            <flux:spacer />
            <flux:button type="submit" variant="primary">{{ __('Save') }}</flux:button>
        </div>
    </form>
</flux:modal>