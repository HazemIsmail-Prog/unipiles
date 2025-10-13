document.addEventListener('alpine:init', () => {
    Alpine.data('projectsComponent', ( companies = [] ) => ({
        companies: companies,
        projects: [],
        current_page:1,
        last_page:1,
        per_page:100,
        total:0,
        form:{},
        submitting: false,
        deleting: null,
        init() {
            this.fetchProjects();
        },
        getEmptyForm() {
            return {
                name_ar: '',
                name_en: '',
                company_id: ''
            }
        },
        fetchProjects() {
            axios.get(`/projects?page=${this.current_page}&per_page=${this.per_page}`)
                .then(response => {
                    this.projects = [...this.projects, ...response.data.data];
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
            this.fetchProjects();
        },
        showEditFormModal(project) {
            this.form = {...project};
            Flux.modal('form-modal').show()
        },
        showCreateFormModal() {
            this.form = this.getEmptyForm();
            Flux.modal('form-modal').show()
        },

        submitForm() {
            if (this.form.id) {
                this.updateProject();
            } else {
                this.storeProject();
            }
        },
        storeProject() {
            this.submitting = true;
            axios.post('/projects', this.form)
                .then(response => {
                    this.projects.unshift(response.data);
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
        updateProject() {
            this.submitting = true;
            axios.put(`/projects/${this.form.id}`, this.form)
                .then(response => {
                    this.projects = this.projects.map(project => project.id === this.form.id ? response.data : project);
                    Flux.modal('form-modal').close();
                })
                .catch(error => {
                    console.error(error);
                })
                .finally(() => {
                    this.submitting = false;
                });
        },
        deleteProject(project) {
            if(!confirm('Are you sure you want to delete this project?')) {
                return;
            }
            this.deleting = project.id;
            axios.delete(`/projects/${project.id}`)
                .then(response => {
                    this.projects = this.projects.filter(p => p.id !== project.id);
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