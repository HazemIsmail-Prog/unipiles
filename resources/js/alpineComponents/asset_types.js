document.addEventListener('alpine:init', () => {
    Alpine.data('assetTypesComponent', () => ({
        assetTypes: [],
        current_page:1,
        last_page:1,
        per_page:100,
        total:0,
        form:{},
        submitting: false,
        deleting: null,
        init() {
            this.fetchAssetTypes();
        },
        getEmptyForm() {
            return {
                name: ''
            }
        },
        fetchAssetTypes() {
            axios.get(`/asset-types?page=${this.current_page}&per_page=${this.per_page}`)
                .then(response => {
                    this.assetTypes = [...this.assetTypes, ...response.data.data];
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
            this.fetchAssetTypes();
        },
        showEditFormModal(assetType) {
            this.form = {...assetType};
            Flux.modal('form-modal').show()
        },
        showCreateFormModal() {
            this.form = this.getEmptyForm();
            Flux.modal('form-modal').show()
        },

        submitForm() {
            if (this.form.id) {
                this.updateAssetType();
            } else {
                this.storeAssetType();
            }
        },
        storeAssetType() {
            this.submitting = true;
            axios.post('/asset-types', this.form)
                .then(response => {
                    this.assetTypes.unshift(response.data);
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
        updateAssetType() {
            this.submitting = true;
            axios.put(`/asset-types/${this.form.id}`, this.form)
                .then(response => {
                    this.assetTypes = this.assetTypes.map(assetType => assetType.id === this.form.id ? response.data : assetType);
                    Flux.modal('form-modal').close();
                })
                .catch(error => {
                    console.error(error);
                })
                .finally(() => {
                    this.submitting = false;
                });
        },
        deleteAssetType(assetType) {
            if(!confirm('Are you sure you want to delete this asset type?')) {
                return;
            }
            this.deleting = assetType.id;
            axios.delete(`/asset-types/${assetType.id}`)
                .then(response => {
                    this.assetTypes = this.assetTypes.filter(a => a.id !== assetType.id);
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