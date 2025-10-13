document.addEventListener('alpine:init', () => {
    Alpine.data('permissionsComponent', () => ({
        permissions: [],
        current_page:1,
        last_page:1,
        per_page:100,
        total:0,
        form:{},
        submitting: false,
        deleting: null,
        init() {
            this.fetchPermissions();
        },
        getEmptyForm() {
            return {
                name: '',
                description_en: '',
                description_ar: '',
            }
        },
        fetchPermissions() {
            axios.get(`/permissions?page=${this.current_page}&per_page=${this.per_page}`)
                .then(response => {
                    this.permissions = [...this.permissions, ...response.data.data];
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
            this.fetchPermissions();
        },
        showEditFormModal(permission) {
            this.form = {...permission};
            Flux.modal('form-modal').show()
        },
        showCreateFormModal() {
            this.form = this.getEmptyForm();
            Flux.modal('form-modal').show()
        },

        submitForm() {
            if (this.form.id) {
                this.updatePermission();
            } else {
                this.storePermission();
            }
        },
        storePermission() {
            this.submitting = true;
            axios.post('/permissions', this.form)
                .then(response => {
                    this.permissions.unshift(response.data);
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
        updatePermission() {
            this.submitting = true;
            axios.put(`/permissions/${this.form.id}`, this.form)
                .then(response => {
                    this.permissions = this.permissions.map(permission => permission.id === this.form.id ? response.data : permission);
                    Flux.modal('form-modal').close();
                })
                .catch(error => {
                    console.error(error);
                })
                .finally(() => {
                    this.submitting = false;
                });
        },
        deletePermission(permission) {
            if(!confirm('Are you sure you want to delete this permission?')) {
                return;
            }
            this.deleting = permission.id;
            axios.delete(`/permissions/${permission.id}`)
                .then(response => {
                    this.permissions = this.permissions.filter(p => p.id !== permission.id);
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