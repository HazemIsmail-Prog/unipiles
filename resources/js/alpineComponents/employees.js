document.addEventListener('alpine:init', () => {
    Alpine.data('employeesComponent', ( titles = [] ) => ({
        titles: titles,
        employees: [],
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
                this.fetchEmployees();
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
                cid: '',
                title_id: '',
                is_active: '',
                sort: 'id',
                sort_direction: 'desc',
            }
        },

        clearFilters() {
            this.filters = this.getEmptyFilters();
        },

        getEmptyForm() {
            return {
                title_id: '',
                name_ar: '',
                name_en: '',
                degree: '',
                cid: '',
                actual_salary: '',
                ezn_salary: '',
                employment_date: '',
                residency: '',
                is_active: true
            }
        },

        fetchEmployees() {
            axios.get(`/employees`,
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
                        this.employees = response.data.data;
                    } else {
                        this.employees = [...this.employees, ...response.data.data];
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
            this.fetchEmployees();
        },
        showEditFormModal(employee) {
            this.form = {...employee};
            Flux.modal('form-modal').show()
        },
        showCreateFormModal() {
            this.form = this.getEmptyForm();
            Flux.modal('form-modal').show()
        },

        submitForm() {
            if (this.form.id) {
                this.updateEmployee();
            } else {
                this.storeEmployee();
            }
        },
        storeEmployee() {
            this.submitting = true;
            axios.post('/employees', this.form)
                .then(response => {
                    this.employees.unshift(response.data);
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
        updateEmployee() {
            this.submitting = true;
            axios.put(`/employees/${this.form.id}`, this.form)
                .then(response => {
                    this.employees = this.employees.map(employee => employee.id === this.form.id ? response.data : employee);
                    Flux.modal('form-modal').close();
                })
                .catch(error => {
                    console.error(error);
                })
                .finally(() => {
                    this.submitting = false;
                });
        },
        deleteEmployee(employee) {
            if(!confirm('Are you sure you want to delete this employee?')) {
                return;
            }
            this.deleting = employee.id;
            axios.delete(`/employees/${employee.id}`)
                .then(response => {
                    this.employees = this.employees.filter(e => e.id !== employee.id);
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
            if(incomingAttachableType === 'employee') {
                let index = this.employees.findIndex(employee => employee.id === attachableId);
                if(index !== -1) {
                    let attachmentIndex = this.employees[index].attachments.findIndex(a => a.id === incomingAttachment.id);
                    if(attachmentIndex !== -1) {
                        this.employees[index].attachments[attachmentIndex] = incomingAttachment;
                    }
                }
            }
        },

        handleAttachmentAddedEvent(event) {
            let incomingAttachment = event.detail;
            let incomingAttachableType = incomingAttachment.attachable_type;
            let attachableId = parseInt(incomingAttachment.attachable_id);
            if(incomingAttachableType === 'employee') {
                let index = this.employees.findIndex(employee => employee.id === attachableId);
                if(index !== -1) {
                    this.employees[index].attachments.push(incomingAttachment);
                }
            }
        },
        
        handleAttachmentDeletedEvent(event) {
            let incomingAttachment = event.detail;
            let incomingAttachableType = incomingAttachment.attachable_type;
            let attachableId = parseInt(incomingAttachment.attachable_id);
            if(incomingAttachableType === 'employee') {
                let index = this.employees.findIndex(employee => employee.id === attachableId);
                if(index !== -1) {
                    this.employees[index].attachments.splice(this.employees[index].attachments.findIndex(a => a.id === incomingAttachment.id), 1);
                }
            }
        },
    }))
})