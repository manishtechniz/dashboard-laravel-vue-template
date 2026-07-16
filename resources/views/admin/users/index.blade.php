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
                        customActions: {
                            test: this.test,
                        }
                    };
                },

                methods: {
                    test(row) {
                        console.log('Parent Test action performed on row:', row);
                    },
                },

                data() {
                    return {
                        show: false,
                        // customActions: [
                        //     {
                        //         title: 'Custom Action 1',
                        //         icon: 'pi pi-cog',
                        //         handler:(rowData) => {
                        //             alert(`Custom Action 1`);
                        //         },
                        //     },
                        //     {
                        //         title: 'Custom Action 2',
                        //         icon: 'pi pi-cog',
                        //         handler: (rowData) => {
                        //             alert(`Custom Action 2`);
                        //         },
                        //     }
                        // ]
                    };
                },



            });
        </script>
    @endPushOnce
</x-admin::layouts>







































