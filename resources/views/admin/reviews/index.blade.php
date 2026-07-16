<x-admin::layouts>
    <div class="page-header">
        <h1 class="page-title">Reviews & Feedback</h1>
        <div class="page-breadcrumb">Home / Reviews</div>
    </div>

    <v-reviews></v-reviews>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-reviews-template">
            <div>
                <!-- Datagrid -->
                <x-admin::datagrid
                    :is-multi-row="true"
                    ref="reviewsGrid"
                    src="{{ route('admin.reviews.index') }}"
                />
                <Toast />
            </div>
        </script>

        <script type="module">
            adminVueApp.component('v-reviews', {
                template: '#v-reviews-template',
                provide() {
                    return {
                        customActions: {
                            delete: this.onDelete
                        }
                    };
                },
                methods: {
                    onDelete(row) {
                        if (confirm('Are you sure you want to delete this review?')) {
                            this.$axios.delete(`{{ route('admin.reviews.index') }}/${row.id}`)
                                .then(response => {
                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                    window.location.reload();
                                });
                        }
                    }
                }
            });
        </script>
    @endPushOnce
</x-admin::layouts>
