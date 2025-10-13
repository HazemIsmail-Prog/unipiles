document.addEventListener('alpine:init', () => {
    Alpine.store('attachments', {
        submitting: false,
        deleting: null,
        form: {},

        getEmptyForm() {
            return {
                id: null,
                attachable_type: null,
                attachable_id: null,
                description_ar: '',
                description_en: '',
                expires_at: null,
                notify_before: null,
                file: null,
            };
        },

        resetForm() {
            Alpine.store('attachments').form = Alpine.store('attachments').getEmptyForm();
            // clear file input
            const fileInput = document.getElementById('fileInput');
            if (fileInput) fileInput.value = null;
        },

        handleFileSelect(event) {
            Alpine.store('attachments').form.file = event.target.files[0];
            const fileName = event.target.files[0]?.name;
            Alpine.store('attachments').form.description_en = fileName?.substring(0, fileName.lastIndexOf('.')) || fileName;
            Alpine.store('attachments').form.description_ar = fileName?.substring(0, fileName.lastIndexOf('.')) || fileName;
        },

        showCreateFormModal(attachable_type, attachable_id) {
            Alpine.store('attachments').form = Alpine.store('attachments').getEmptyForm();
            Alpine.store('attachments').form.attachable_type = attachable_type;
            Alpine.store('attachments').form.attachable_id = attachable_id;
            Flux.modal('attachment-form-modal').show();
        },

        showEditFormModal(attachment) {
            Alpine.store('attachments').form = { ...attachment };
            Flux.modal('attachment-form-modal').show();
        },

        submitForm() {
            if (Alpine.store('attachments').form.id) {
                Alpine.store('attachments').updateAttachment();
            } else {
                Alpine.store('attachments').storeAttachment();
            }
        },

        storeAttachment() {
            Alpine.store('attachments').submitting = true;
            const formData = new FormData();
            formData.append('description_ar', Alpine.store('attachments').form.description_ar);
            formData.append('description_en', Alpine.store('attachments').form.description_en);
            if (Alpine.store('attachments').form.expires_at) {
                formData.append('expires_at', Alpine.store('attachments').form.expires_at);
            }
            if (Alpine.store('attachments').form.notify_before) {
                formData.append('notify_before', Alpine.store('attachments').form.notify_before);
            }
            formData.append('file', Alpine.store('attachments').form.file);
            formData.append('attachable_type', Alpine.store('attachments').form.attachable_type);
            formData.append('attachable_id', Alpine.store('attachments').form.attachable_id);
            axios.post('/attachments', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(response => {
                window.dispatchEvent(new CustomEvent('attachment-added', { detail: response.data }));
                Flux.modal('attachment-form-modal').close();
            })
            .catch(error => {
                console.error(error);
            })
            .finally(() => {
                Alpine.store('attachments').submitting = false;
            });
        },

        updateAttachment() {
            Alpine.store('attachments').submitting = true;
            const formData = new FormData();
            formData.append('description_ar', Alpine.store('attachments').form.description_ar);
            formData.append('description_en', Alpine.store('attachments').form.description_en);
            formData.append('expires_at', Alpine.store('attachments').form.expires_at);
            formData.append('notify_before', Alpine.store('attachments').form.notify_before);
            if (Alpine.store('attachments').form.file) {
                formData.append('file', Alpine.store('attachments').form.file);
            }
            formData.append('attachable_type', Alpine.store('attachments').form.attachable_type);
            formData.append('attachable_id', Alpine.store('attachments').form.attachable_id);
            formData.append('_method', 'PUT');

            axios.post(`/attachments/${Alpine.store('attachments').form.id}`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then(response => {
                    window.dispatchEvent(new CustomEvent('attachment-updated', { detail: response.data }));
                    Flux.modal('attachment-form-modal').close();
                })
                .catch(error => {
                    console.error(error);
                })
                .finally(() => {
                    Alpine.store('attachments').submitting = false;
                });
        },


        deleteAttachment(attachment) {
            if(!confirm('Are you sure you want to delete this attachment?')) {
                return;
            }
            Alpine.store('attachments').deleting = attachment.id;

            axios.delete(`/attachments/${attachment.id}`)
                .then(response => {
                    window.dispatchEvent(new CustomEvent('attachment-deleted', { detail: response.data }));
                })
                .catch(error => {
                    console.error(error);
                })
                .finally(() => {
                    Alpine.store('attachments').deleting = null;
                });
        },

    });
});
