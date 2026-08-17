<x-admin::layouts>
    <div class="page-header">
        <h1 class="page-title">Notification Management</h1>
        <div class="page-breadcrumb">Home / Notifications</div>
    </div>

    <v-notifications
        :clients='@json($clients)'
        :device-tokens='@json($deviceTokens)'></v-notifications>

    @pushOnce('scripts')
    <script type="text/x-template" id="v-notifications-template">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Send Notification Panel -->
                <div class="card p-6 shadow-sm h-fit border border-(--border) bg-(--bg-surface)">
                    <div class="flex items-center gap-2 mb-6 border-b border-(--border) pb-3">
                        <i class="pi pi-send text-(--accent) text-lg"></i>
                        <h2 class="text-base font-bold text-(--text-base)">Compose Message</h2>
                    </div>

                    <x-admin::form v-slot="{ meta, errors, handleSubmit }" as="div">
                        <form @submit="handleSubmit($event, sendNotification)" class="space-y-4">
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Target Client" />
                                <x-admin::form.control-group.control
                                    v-model="notification.client_id"
                                    ::value="notification.client_id"
                                    ::options="clientsOptions"
                                    optionLabel="name"
                                    optionValue="id"  
                                    name="client_id"
                                    type="multiselect"
                                    placeholder="Select Client"
                                    rules="required"
                                    filter
                                /> 
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Title" />
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="title"
                                    v-model="notification.title"
                                    rules="required"
                                    placeholder="Enter notification title"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Body" />
                                <x-admin::form.control-group.control
                                    type="textarea"
                                    name="body"
                                    v-model="notification.body"
                                    rules="required"
                                    placeholder="Enter message body"
                                />
                            </x-admin::form.control-group> 

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Type" />
                                <x-admin::form.control-group.control
                                    type="select"
                                    v-model="notification.type"
                                    ::value="notification.type"
                                    ::options="{{ json_encode($eventTypes) }}"
                                    optionLabel="label"
                                    optionValue="value"
                                    placeholder="Select Type" 
                                    rules="required"
                                    name="type"
                                />
                            </x-admin::form.control-group>

                            <div class="pt-4">
                                <Button type="submit" label="Send Broadcast" icon="pi pi-send" class="w-full" :loading="loading" />
                            </div>
                        </form>
                    </x-admin::form>
                </div>

                <!-- History and Device Tokens List -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- History -->
                    <div class="card p-6 shadow-sm border border-(--border) bg-(--bg-surface)">
                        <div class="flex items-center justify-between mb-6 border-b border-(--border) pb-3">
                            <div class="flex items-center gap-2">
                                <i class="pi pi-list text-(--accent) text-lg"></i>
                                <h2 class="text-base font-bold text-(--text-base)">Sent Notifications</h2>

                                <Button
                                    label=""
                                    icon="pi pi-refresh"
                                    :loading="isLoadingList"
                                    class="p-button-outlined"
                                    size="small"
                                    @click="refreshList"
                                />
                            </div>
                            <span v-if="total !== null && total !== undefined" class="text-xs px-2.5 py-1 rounded-full bg-(--accent-light) text-(--accent) font-medium border border-(--border)">
                                @{{ items.length }} of @{{ total }}
                            </span>
                        </div>
                        <div 
                            ref="scrollContainer" 
                            @scroll="handleScroll" 
                            class="space-y-4 max-h-[470px] overflow-y-auto pr-1"
                        >
                            <div 
                                v-for="notif in items" 
                                :key="notif.id" 
                                class="p-3 bg-(--bg-subtle) rounded-lg border border-(--border) flex justify-between items-start gap-4 transition-all hover:bg-(--bg-surface) hover:border-(--accent) hover:shadow-sm flex-col-reverse md:flex-row"
                            >
                                <div class="flex-1">
                                    <div class="flex gap-2 items-center flex-wrap">
                                        <h4 class="font-bold text-sm text-(--text-base)">@{{ notif.title }}</h4>
                                         
                                        <span :class="notif?.type_color || 'badge badge-info'"> @{{ notif?.type_label || formatType(notif?.type) }} </span>
                                    </div>
                                    <p class="text-xs text-(--text-muted) mt-1">@{{ notif.body }}</p>
                                    <span class="text-[10px] text-(--text-muted) mt-2 block">
                                        To: @{{ notif.client ? notif.client.name : (notif.client_id ? 'Client #' + notif.client_id : 'All Clients') }}
                                    </span>
                                </div>
                                <span class="text-[10px] text-(--text-muted) whitespace-nowrap">@{{ formatTime(notif.created_at) }}</span>
                            </div>

                            <!-- Loading Spinner for Infinite Scroll -->
                            <div v-if="loadingMore" class="flex items-center justify-center py-3 gap-2 text-xs text-(--accent)">
                                <i class="pi pi-spin pi-spinner text-sm"></i>
                                <span>Loading more notifications...</span>
                            </div>

                            <!-- End of List Notice -->
                            <div v-if="!hasMore && items.length > 0" class="text-center py-3 text-xs text-(--text-muted) border-t border-(--border)">
                                All notifications loaded
                            </div>

                            <!-- Empty State -->
                            <div v-if="items.length === 0 && !loadingMore" class="text-center py-6 text-(--text-muted)">
                                No notifications sent yet.
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </script>

    <script type="module">
        adminVueApp.component('v-notifications', {
            template: '#v-notifications-template',
            props: ['clients', 'deviceTokens'],
            data() {
                return {
                    loading: false,
                    loadingMore: false,
                    items: [],
                    page: 0,
                    lastPage: 0,
                    total: 0,
                    hasMore: true,
                    isLoadingList: false,
                    notification: {
                        client_id: null,
                        title: '',
                        body: '',
                        type: null
                    },
                    clientsOptions: this.clients
                };
            },
            mounted() {
                this.$nextTick(() => {
                    this.loadMore();

                    this.checkContainerScroll();
                });
            },
            methods: {
                formatType(type) {
                    if (!type) return 'General';
                    return type.charAt(0).toUpperCase() + type.slice(1).replace(/_/g, ' ');
                },
                truncateToken(token) {
                    return token ? token.substring(0, 30) + '...' : '';
                },
                formatTime(timeStr) {
                    if (!timeStr) return '';
                    const date = new Date(timeStr);
                    return isNaN(date.getTime()) ? timeStr : date.toLocaleString();
                },
                handleScroll(event) {
                    const target = event.target;
                    const scrollBottom = target.scrollHeight - target.scrollTop - target.clientHeight;
                    if (scrollBottom <= 60 && this.hasMore && !this.loadingMore) {
                        this.loadMore();
                    }
                },
                checkContainerScroll() {
                    const el = this.$refs.scrollContainer;
                    if (el && el.scrollHeight <= el.clientHeight && this.hasMore && !this.loadingMore) {
                        this.loadMore();
                    }
                },
                loadMore() {
                    if (this.loadingMore || !this.hasMore) return;

                    this.loadingMore = true;
                    const nextPage = this.page + 1;

                    this.$axios.get("{{ route('admin.notifications.index') }}", {
                            params: {
                                page: nextPage
                            }
                        })
                        .then(response => {
                            const data = response.data;
                            const newItems = data.data || [];
                            this.items.push(...newItems);
                            this.page = data.current_page || nextPage;
                            this.lastPage = data.last_page || this.lastPage;
                            this.total = data.total ?? this.total;
                            this.hasMore = this.page < this.lastPage;
                            this.loadingMore = false;

                            this.$nextTick(() => {
                                this.checkContainerScroll();
                            });
                        })
                        .catch(error => {
                            console.error('Failed to load more notifications:', error);
                            this.loadingMore = false;
                        });
                },
                sendNotification(params, {
                    resetForm,
                    setErrors
                }) {
                    this.loading = true;
                    const payload = {
                        ...params,
                        client_id: this.notification.client_id,
                        type: this.notification.type
                    };

                    this.$axios.post("{{ route('admin.notifications.store') }}", payload)
                        .then(response => {
                            this.$emitter.emit('add-flash', {
                                type: 'success',
                                message: response.data.message
                            });

                            this.loading = false;

                            this.notification = {
                                client_id: null,
                                title: '',
                                body: '',
                                type: null
                            };

                            resetForm();
                            this.refreshList();
                        })
                        .catch(error => {
                            this.loading = false;

                            if (error.response.status === 500) {
                                this.$emitter.emit('add-flash', {
                                    type: 'error',
                                    message: error.response.data.message
                                });

                                return;
                            }

                            if (error.response.status === 422) {
                                if (error.response.data.errors?.avatar) {
                                    this.$emitter.emit('add-flash', {
                                        type: 'error',
                                        message: error.response.data.errors?.avatar[0]
                                    });
                                }

                                setErrors(error.response.data.errors);
                            }
                        });
                },
                refreshList() {
                    this.isLoadingList = true;
                    this.$axios.get("{{ route('admin.notifications.index') }}", {
                            params: {
                                page: 1
                            }
                        })
                        .then(response => {
                            const data = response.data;
                            this.items = data.data || [];
                            this.page = 1;
                            this.lastPage = data.last_page || 1;
                            this.total = data.total ?? this.items.length;
                            this.hasMore = this.page < this.lastPage;
                            this.isLoadingList = false;
                        })
                        .catch(error => {
                            this.isLoadingList = false;

                            console.error('Failed to refresh notifications:', error);
                        });
                }
            }
        });
    </script>
    @endPushOnce
</x-admin::layouts>