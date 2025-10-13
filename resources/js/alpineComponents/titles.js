document.addEventListener('alpine:init', () => {
    Alpine.data('titlesComponent', () => ({
        titles: [],
        current_page:1,
        last_page:1,
        per_page:100,
        total:0,
        form:{},
        submitting: false,
        deleting: null,
        init() {
            this.fetchTitles();
        },
        getEmptyForm() {
            return {
                name_ar: '',
                name_en: ''
            }
        },
        fetchTitles() {
            axios.get(`/titles?page=${this.current_page}&per_page=${this.per_page}`)
                .then(response => {
                    this.titles = [...this.titles, ...response.data.data];
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
            this.fetchTitles();
        },
        showEditFormModal(title) {
            this.form = {...title};
            Flux.modal('form-modal').show()
        },
        showCreateFormModal() {
            this.form = this.getEmptyForm();
            Flux.modal('form-modal').show()
        },

        submitForm() {
            if (this.form.id) {
                this.updateTitle();
            } else {
                this.storeTitle();
            }
        },
        storeTitle() {
            this.submitting = true;
            axios.post('/titles', this.form)
                .then(response => {
                    this.titles.unshift(response.data);
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
        updateTitle() {
            this.submitting = true;
            axios.put(`/titles/${this.form.id}`, this.form)
                .then(response => {
                    this.titles = this.titles.map(title => title.id === this.form.id ? response.data : title);
                    Flux.modal('form-modal').close();
                })
                .catch(error => {
                    console.error(error);
                })
                .finally(() => {
                    this.submitting = false;
                });
        },
        deleteTitle(title) {
            if(!confirm('Are you sure you want to delete this title?')) {
                return;
            }
            this.deleting = title.id;
            axios.delete(`/titles/${title.id}`)
                .then(response => {
                    this.titles = this.titles.filter(t => t.id !== title.id);
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