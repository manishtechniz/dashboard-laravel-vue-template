<x-admin::layouts>

    <div class="page-header">
        <h1 class="page-title">Module Name</h1>
        <div class="page-breadcrumb">Home / Module</div>
    </div>

    <v-demo />

    @pushOnce('scripts')
        <script type="text/x-template" id="v-demo-template">
            <x-admin::datagrid
                :is-multi-row="true"  
                ref="dg12"
                src="{{ route('admin.users.index')}}"
            /> 
        </script>

        <script type="module">
            adminVueApp.component('v-demo', {
                template: '#v-demo-template',

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
                    };
                },



            });
        </script>
    @endPushOnce
</x-admin::layouts>







































