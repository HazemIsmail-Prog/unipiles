document.addEventListener('alpine:init', () => {
    Alpine.data('companiesComponent', () => ({
        companies: [],
        current_page:1,
        last_page:1,
        per_page:100,
        total:0,
        form:{},
        submitting: false,
        deleting: null,
        init() {
            this.fetchCompanies();
        },
        getEmptyForm() {
            return {
                name_ar: '',
                name_en: ''
            }
        },
        fetchCompanies() {
            axios.get(`/companies?page=${this.current_page}&per_page=${this.per_page}`)
                .then(response => {
                    this.companies = [...this.companies, ...response.data.data];
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
            this.fetchCompanies();
        },
        showEditFormModal(company) {
            this.form = {...company};
            Flux.modal('form-modal').show()
        },
        showCreateFormModal() {
            this.form = this.getEmptyForm();
            Flux.modal('form-modal').show()
        },

        submitForm() {
            if (this.form.id) {
                this.updateCompany();
            } else {
                this.storeCompany();
            }
        },
        storeCompany() {
            this.submitting = true;
            axios.post('/companies', this.form)
                .then(response => {
                    this.companies.unshift(response.data);
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
        updateCompany() {
            this.submitting = true;
            axios.put(`/companies/${this.form.id}`, this.form)
                .then(response => {
                    this.companies = this.companies.map(company => company.id === this.form.id ? response.data : company);
                    Flux.modal('form-modal').close();
                })
                .catch(error => {
                    console.error(error);
                })
                .finally(() => {
                    this.submitting = false;
                });
        },
        deleteCompany(company) {
            if(!confirm('Are you sure you want to delete this company?')) {
                return;
            }
            this.deleting = company.id;
            axios.delete(`/companies/${company.id}`)
                .then(response => {
                    this.companies = this.companies.filter(c => c.id !== company.id);
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