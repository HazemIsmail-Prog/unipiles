document.addEventListener('alpine:init', () => {
    Alpine.data('quotationsComponent', () => ({
        quotations: [],
        current_page:1,
        last_page:1,
        per_page:100,
        total:0,
        form:{},
        submitting: false,
        deleting: null,
        filters:{},

        init() {
            this.$watch('filters', () => {
                this.current_page = 1;
                this.fetchQuotations();
            });
            this.filters = this.getEmptyFilters();
        },

        setSorting($column){
            if (this.filters.sort === $column) {
                this.filters.sort_direction = this.filters.sort_direction === 'asc' ? 'desc' : 'asc';
            } else {
                this.filters.sort = $column;
                this.filters.sort_direction = 'asc';
            }
        },

        getEmptyFilters() {
            return {
                date: '',
                ref: '',
                sent_to: '',
                subject: '',
                project: '',
                sort: 'id',
                sort_direction: 'desc',
            }
        },

        clearFilters() {
            this.filters = this.getEmptyFilters();
        },

        getEmptyForm() {
            return {
                date: '',
                ref: '',
                sent_to: '',
                subject: '',
                project: '',
            }
        },
        
        fetchQuotations() {
            axios.get(`/quotations?`,
                {
                    params: {
                        per_page: this.per_page,
                        page: this.current_page,
                        filters: this.filters,
                    }
                }
            )
                .then(response => {
                    if (this.current_page === 1) {
                        this.quotations = response.data.data;
                    } else {
                        this.quotations = [...this.quotations, ...response.data.data];
                    }
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
            this.fetchQuotations();
        },
        showEditFormModal(quotation) {
            this.form = {...quotation};
            Flux.modal('form-modal').show()
        },
        showCreateFormModal() {
            this.form = this.getEmptyForm();
            Flux.modal('form-modal').show()
        },

        submitForm() {
            if (this.form.id) {
                this.updateQuotation();
            } else {
                this.storeQuotation();
            }
        },
        storeQuotation() {
            this.submitting = true;
            axios.post('/quotations', this.form)
                .then(response => {
                    this.quotations.unshift(response.data);
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
        updateQuotation() {
            this.submitting = true;
            axios.put(`/quotations/${this.form.id}`, this.form)
                .then(response => {
                    this.quotations = this.quotations.map(quotation => quotation.id === this.form.id ? response.data : quotation);
                    Flux.modal('form-modal').close();
                })
                .catch(error => {
                    console.error(error);
                })
                .finally(() => {
                    this.submitting = false;
                });
        },
        deleteQuotation(quotation) {
            if(!confirm('Are you sure you want to delete this quotation?')) {
                return;
            }
            this.deleting = quotation.id;
            axios.delete(`/quotations/${quotation.id}`)
                .then(response => {
                    this.quotations = this.quotations.filter(q => q.id !== quotation.id);
                    this.total--;
                })
                .catch(error => {
                    console.error(error);
                })
                .finally(() => {
                    this.deleting = null;
                });
        },
        // Event Handlers


        handleAttachmentUpdatedEvent(event) {
            let incomingAttachment = event.detail;
            let incomingAttachableType = incomingAttachment.attachable_type;
            let attachableId = parseInt(incomingAttachment.attachable_id);
            if(incomingAttachableType === 'quotation') {
                let index = this.quotations.findIndex(quotation => quotation.id === attachableId);
                if(index !== -1) {
                    let attachmentIndex = this.quotations[index].attachments.findIndex(a => a.id === incomingAttachment.id);
                    if(attachmentIndex !== -1) {
                        this.quotations[index].attachments[attachmentIndex] = incomingAttachment;
                    }
                }
            }
        },

        handleAttachmentAddedEvent(event) {
            let incomingAttachment = event.detail;
            let incomingAttachableType = incomingAttachment.attachable_type;
            let attachableId = parseInt(incomingAttachment.attachable_id);
            if(incomingAttachableType === 'quotation') {
                let index = this.quotations.findIndex(quotation => quotation.id === attachableId);
                if(index !== -1) {
                    this.quotations[index].attachments.push(incomingAttachment);
                }
            }
        },
        
        handleAttachmentDeletedEvent(event) {
            let incomingAttachment = event.detail;
            let incomingAttachableType = incomingAttachment.attachable_type;
            let attachableId = parseInt(incomingAttachment.attachable_id);
            if(incomingAttachableType === 'quotation') {
                let index = this.quotations.findIndex(quotation => quotation.id === attachableId);
                if(index !== -1) {
                    this.quotations[index].attachments.splice(this.quotations[index].attachments.findIndex(a => a.id === incomingAttachment.id), 1);
                }
            }
        },
    }))
})