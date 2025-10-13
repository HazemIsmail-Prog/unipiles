document.addEventListener('alpine:init', () => {
    Alpine.data('rolesComponent', ( permissions = [] ) => ({
        permissions: permissions,
        roles: [],
        current_page:1,
        last_page:1,
        per_page:100,
        total:0,
        form:{},
        submitting: false,
        deleting: null,
        init() {
            this.fetchRoles();
        },
        getEmptyForm() {
            return {
                name: '',
                permissions: [],
            }
        },
        fetchRoles() {
            axios.get(`/roles?page=${this.current_page}&per_page=${this.per_page}`)
                .then(response => {
                    this.roles = [...this.roles, ...response.data.data];
                    this.current_page = response.data.current_page;
                    this.last_page = response.data.last_page;
                    this.per_page = response.data.per_page;
                    this.total = response.data.total;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        loadMore() {
            this.current_page++;
            this.fetchRoles();
        },
        showEditFormModal(role) {
            this.form = {...role};
            this.form.permissions = this.form.permissions.map(permission => permission.id);
            Flux.modal('form-modal').show()
        },
        showCreateFormModal() {
            this.form = this.getEmptyForm();
            Flux.modal('form-modal').show()
        },

        submitForm() {
            if (this.form.id) {
                this.updateRole();
            } else {
                this.storeRole();
            }
        },
        storeRole() {
            this.submitting = true;
            axios.post('/roles', this.form)
                .then(response => {
                    this.roles.unshift(response.data);
                    Flux.modal('form-modal').close();
                    this.total++;
                })
                .catch(error => {
                    console.error(error);
                })
                .finally(() => {
                    this.submitting = false;
                });
        },
        updateRole() {
            this.submitting = true;
            axios.put(`/roles/${this.form.id}`, this.form)
                .then(response => {
                    this.roles = this.roles.map(role => role.id === this.form.id ? response.data : role);
                    Flux.modal('form-modal').close();
                })
                .catch(error => {
                    console.error(error);
                })
                .finally(() => {
                    this.submitting = false;
                });
        },
        deleteRole(role) {
            if(!confirm('Are you sure you want to delete this role?')) {
                return;
            }
            this.deleting = role.id;
            axios.delete(`/roles/${role.id}`)
                .then(response => {
                    this.roles = this.roles.filter(r => r.id !== role.id);
                    this.total--;
                })
                .catch(error => {
                    console.error(error);
                })
                .finally(() => {
                    this.deleting = null;
                });
        }
    }))
})