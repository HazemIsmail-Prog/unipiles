document.addEventListener('alpine:init', () => {
    Alpine.data('documentsComponent', ( projects = [] , project_id = null ) => ({
        projects: projects,
        documents: [],
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
                this.fetchDocuments();
            });
            this.filters = this.getEmptyFilters();
            if (project_id) {
                this.filters.project_id = project_id;
            }
        },

        setSorting($column){
            if (this.filters.sort === $column) {
                // If already sorting by this column, toggle the sort direction
                this.filters.sort_direction = this.filters.sort_direction === 'asc' ? 'desc' : 'asc';
            } else {
                // Otherwise, set to this column, and default to ascending order
                this.filters.sort = $column;
                this.filters.sort_direction = 'asc';
            }
        },

        getEmptyFilters() {
            return {
                date: '',
                type: '',
                ref: '',
                subject: '',
                sent_from: '',
                sent_to: '',
                project_id: '',
                sort: 'id',
                sort_direction: 'desc',
            }
        },

        clearFilters() {
            this.filters = this.getEmptyFilters();
        },

        getEmptyForm() {
            return {
                project_id: '',
                date: '',
                type: '',
                ref: '',
                subject: '',
                description: '',
                sent_from: '',
                sent_to: ''
            };
        },
        fetchDocuments() {
            axios.get(`/documents`,
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
                        this.documents = response.data.data;
                    } else {
                        this.documents = [...this.documents, ...response.data.data];
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
            this.fetchDocuments();
        },
        showEditFormModal(document) {
            this.form = {...document};
            Flux.modal('form-modal').show()
        },
        showCreateFormModal() {
            this.form = this.getEmptyForm();
            Flux.modal('form-modal').show()
        },

        submitForm() {
            if (this.form.id) {
                this.updateDocument();
            } else {
                this.storeDocument();
            }
        },
        storeDocument() {
            this.submitting = true;
            axios.post('/documents', this.form)
                .then(response => {
                    this.documents.unshift(response.data);
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
        updateDocument() {
            this.submitting = true;
            axios.put(`/documents/${this.form.id}`, this.form)
                .then(response => {
                    this.documents = this.documents.map(document => document.id === this.form.id ? response.data : document);
                    Flux.modal('form-modal').close();
                })
                .catch(error => {
                    console.error(error);
                })
                .finally(() => {
                    this.submitting = false;
                });
        },
        deleteDocument(document) {
            if(!confirm('Are you sure you want to delete this document?')) {
                return;
            }
            this.deleting = document.id;
            axios.delete(`/documents/${document.id}`)
                .then(response => {
                    this.documents = this.documents.filter(d => d.id !== document.id);
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
            if(incomingAttachableType === 'document') {
                let index = this.documents.findIndex(document => document.id === attachableId);
                if(index !== -1) {
                    let attachmentIndex = this.documents[index].attachments.findIndex(a => a.id === incomingAttachment.id);
                    if(attachmentIndex !== -1) {
                        this.documents[index].attachments[attachmentIndex] = incomingAttachment;
                    }
                }
            }
        },

        handleAttachmentAddedEvent(event) {
            let incomingAttachment = event.detail;
            let incomingAttachableType = incomingAttachment.attachable_type;
            let attachableId = parseInt(incomingAttachment.attachable_id);
            if(incomingAttachableType === 'document') {
                let index = this.documents.findIndex(document => document.id === attachableId);
                if(index !== -1) {
                    this.documents[index].attachments.push(incomingAttachment);
                }
            }
        },
        
        handleAttachmentDeletedEvent(event) {
            let incomingAttachment = event.detail;
            let incomingAttachableType = incomingAttachment.attachable_type;
            let attachableId = parseInt(incomingAttachment.attachable_id);
            if(incomingAttachableType === 'document') {
                let index = this.documents.findIndex(document => document.id === attachableId);
                if(index !== -1) {
                    this.documents[index].attachments.splice(this.documents[index].attachments.findIndex(a => a.id === incomingAttachment.id), 1);
                }
            }
        },
            
    }))
})