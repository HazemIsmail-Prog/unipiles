document.addEventListener('alpine:init', () => {
    Alpine.data('assetsComponent', ( asset_types = [] ) => ({
        asset_types: asset_types,
        assets: [],
        current_page:1,
        last_page:1,
        per_page:1000,
        total:0,
        form:{},
        submitting: false,
        deleting: null,
        filters:{},
        
        init() {
            this.$watch('filters', () => {
                this.current_page = 1;
                this.fetchAssets();
            });
            this.filters = this.getEmptyFilters();
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
                name: '',
                serial: '',
                sub_category_name: '',
                asset_type_id: '',
                sort: 'id',
                sort_direction: 'desc',
            }
        },

        clearFilters() {
            this.filters = this.getEmptyFilters();
        },

        getEmptyForm() {
            return {
                id: null,
                name_ar: '',
                name_en: '',
                serial: '',
                sub_category_name: '',
                asset_type_id: '',
                is_active: true,
            }
        },


        fetchAssets() {
            axios.get(`/assets`,
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
                        this.assets = response.data.data;
                    } else {
                        this.assets = [...this.assets, ...response.data.data];
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
            this.fetchAssets();
        },
        showEditFormModal(asset) {
            this.form = {...asset};
            Flux.modal('form-modal').show()
        },
        showCreateFormModal() {
            this.form = this.getEmptyForm();
            Flux.modal('form-modal').show()
        },

        submitForm() {
            if (this.form.id) {
                this.updateAsset();
            } else {
                this.storeAsset();
            }
        },

        storeAsset() {
            this.submitting = true;
            axios.post('/assets', this.form)
                .then(response => {
                    this.assets.unshift(response.data);
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

        updateAsset() {
            this.submitting = true;
            axios.put(`/assets/${this.form.id}`, this.form)
                .then(response => {
                    this.assets = this.assets.map(asset => asset.id === this.form.id ? response.data : asset);
                    Flux.modal('form-modal').close();
                })
                .catch(error => {
                    console.error(error);
                })
                .finally(() => {
                    this.submitting = false;
                });
        },

        deleteAsset(asset) {
            if(!confirm('Are you sure you want to delete this asset?')) {
                return;
            }
            this.deleting = asset.id;
            axios.delete(`/assets/${asset.id}`)
                .then(response => {
                    this.assets = this.assets.filter(a => a.id !== asset.id);
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
            if(incomingAttachableType === 'asset') {
                let index = this.assets.findIndex(asset => asset.id === attachableId);
                if(index !== -1) {
                    let attachmentIndex = this.assets[index].attachments.findIndex(a => a.id === incomingAttachment.id);
                    if(attachmentIndex !== -1) {
                        this.assets[index].attachments[attachmentIndex] = incomingAttachment;
                    }
                }
            }
        },

        handleAttachmentAddedEvent(event) {
            let incomingAttachment = event.detail;
            let incomingAttachableType = incomingAttachment.attachable_type;
            let attachableId = parseInt(incomingAttachment.attachable_id);
            if(incomingAttachableType === 'asset') {
                let index = this.assets.findIndex(asset => asset.id === attachableId);
                if(index !== -1) {
                    this.assets[index].attachments.push(incomingAttachment);
                }
            }
        },
        
        handleAttachmentDeletedEvent(event) {
            let incomingAttachment = event.detail;
            let incomingAttachableType = incomingAttachment.attachable_type;
            let attachableId = parseInt(incomingAttachment.attachable_id);
            if(incomingAttachableType === 'asset') {
                let index = this.assets.findIndex(asset => asset.id === attachableId);
                if(index !== -1) {
                    this.assets[index].attachments.splice(this.assets[index].attachments.findIndex(a => a.id === incomingAttachment.id), 1);
                }
            }
        },
    }))
})