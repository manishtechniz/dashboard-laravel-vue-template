<x-admin::layouts>
    <div class="page-header">
        <h1 class="page-title">User Management</h1>
        <div class="page-breadcrumb">Home / Users</div>
    </div>
 
    <v-test />

    @pushOnce('scripts')
        <script type="text/x-template" id="v-test-template">
            <x-admin::datagrid 
                src="{{ route('admin.users.index')}}"
            />
        </script>

        <script type="module">
            adminVueApp.component('v-test', {
                template: '#v-test-template',

                provide() {
                    return {
                        /**
                         * Use "customActions" variable for provode custom operation into datagrid
                         */
                        customActions: {
                            edit: this.editOperation, 
                        }
                    };
                },
                methods: {
                    /**
                     * Record: In this param, this table record will be passed.
                     * 
                     * So using this variable you can perform any operation.
                     * For example: Open modal, Redirect to page, Open popup, etc.
                     * 
                     */
                    editOperation(record) {
                        // Custom operation
                    }
                } 
            });
        </script>
    @endPushOnce
</x-admin::layouts>







































