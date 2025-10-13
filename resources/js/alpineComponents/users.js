document.addEventListener('alpine:init', () => {
    Alpine.data('usersComponent', (roles = [], permissions = []) => ({
        roles: roles,
        permissions: permissions,
        users: [],
        current_page:1,
        last_page:1,
        per_page:100,
        total:0,
        form:{},
        submitting: false,
        deleting: null,
        file: null,
        fileInput: null,
        init() {
            this.fetchUsers();
        },
        getEmptyForm() {
            return {
                name: '',
                email: '',
                password: '',
                roles: [],
                permissions: [],
            }
        },
        fetchUsers() {
            axios.get(`/users?page=${this.current_page}&per_page=${this.per_page}`)
                .then(response => {
                    this.users = [...this.users, ...response.data.data];
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
            this.fetchUsers();
        },
        showEditFormModal(user) {
            this.form = {...user};
            this.form.roles = user.roles.map(role => role.id);
            this.form.permissions = user.permissions.map(permission => permission.id);
            Flux.modal('form-modal').show()
        },
        showCreateFormModal() {
            this.form = this.getEmptyForm();
            Flux.modal('form-modal').show()
        },

        submitForm() {
            if (this.form.id) {
                this.updateUser();
            } else {
                this.storeUser();
            }
        },
        storeUser() {
            this.submitting = true;
            axios.post('/users', this.form)
                .then(response => {
                    this.users.unshift(response.data);
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
        updateUser() {
            this.submitting = true;
            axios.put(`/users/${this.form.id}`, this.form)
                .then(response => {
                    this.users = this.users.map(user => user.id === this.form.id ? response.data : user);
                    Flux.modal('form-modal').close();
                })
                .catch(error => {
                    console.error(error);
                })
                .finally(() => {
                    this.submitting = false;
                });
        },
        deleteUser(user) {
            if(!confirm('Are you sure you want to delete this user?')) {
                return;
            }
            this.deleting = user.id;
            axios.delete(`/users/${user.id}`)
                .then(response => {
                    this.users = this.users.filter(u => u.id !== user.id);
                    this.total--;
                })
                .catch(error => {
                    console.error(error);
                })
                .finally(() => {
                    this.deleting = null;
                });
        },

        toggleList(event,id,list) {
            if(event.target.checked) {
                list = [...list, id];
            } else {
                list = list.filter(item => item !== id);
            }
        },

        handleFileSelect(event) {
            this.file = event.target.files[0];
            this.fileInput = event.target;
        },
        
        uploadFile() {
            if (!this.file) {
                alert('Please select a file first');
                return;
            }
            
            this.submitting = true;
            const formData = new FormData();
            formData.append('file', this.file);
            
            axios.post('/users/upload', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                }
            })
            .then(response => {
                console.log('File uploaded successfully:', response.data);
                alert('File uploaded successfully!');
                // Reset file input
                if (this.fileInput) {
                    this.fileInput.value = '';
                }
                this.file = null;
            })
            .catch(error => {
                console.error('Upload error:', error);
                if (error.response && error.response.data && error.response.data.message) {
                    alert('Upload failed: ' + error.response.data.message);
                } else {
                    alert('Upload failed. Please try again.');
                }
            })
            .finally(() => {
                this.submitting = false;
            });
        }
    }))
})