@props([
    'isPagination' => true,
])

<v-quicktable {{ $attributes }}>
</v-quicktable>

@pushOnce('scripts')
    <script
    type="text/x-template"
    id="v-quicktable-template"
>
 
    <div class="space-y-4 pt-2 ">
        {{-- Search --}}
        {{-- <div class="flex justify-between mb-1">
            <div class="relative content-center">
                <i class="pi pi-search absolute left-3 top-1/2 -translate-y-1/2" style="color: var(--text-muted);"></i>
    
                <InputText
                    :fluid="false"
                    placeholder="Search..."
                    class="w-3xs! pl-9 text-sm pl-8!"
                    @keyup.enter="search"
                    :value="getQuickSearchVales('all')"
                />
            </div> 
        </div> --}}

        {{-- Table --}}
        <div class="border rounded-lg overflow-hidden" style="border-color: var(--border);">
            <template v-if="isLoading">
                <x-admin::shimmer.quicktable />
            </template>

            <div class="overflow-x-auto" style="
                scrollbar-width: thin;
                max-width: 100%;
            "
            v-else
            >
                <table class="w-full text-left border-collapse">

                    {{-- Header --}}
                    <thead>

                        <tr class="border-b" style="background-color: var(--bg-subtle); border-color: var(--border);">
                            <th v-for="(col, idx) in available.columns" :key="'qtc_' + idx"
                                class="p-3 text-xs font-bold uppercase select-none whitespace-nowrap"
                                :class="col.sortable ? 'cursor-pointer' : ''" style="color: var(--text-muted);"
                                @click="sort(col)">

                                @{{ col.label }}

                                <i v-if="col.sortable" :class="
                                                applied.sort.column === col.index
                                                    ? (
                                                        applied.sort.order === 1
                                                            ? 'pi pi-sort-amount-up'
                                                            : 'pi pi-sort-amount-down'
                                                    )
                                                    : 'pi pi-sort'
                                            " class="ml-1 text-[10px]"></i>

                            </th>

                            <th v-if="available.actions.length" class="p-3 text-xs font-bold uppercase text-right"
                                style="color: var(--text-muted);">
                                Actions
                            </th>

                        </tr>

                    </thead>

                    {{-- Body --}}
                    <tbody>
                        {{-- Records --}}
                        <tr v-for="record in available.records" :key="'qtr_' + record.id"
                            class="border-b hover:bg-[var(--bg-subtle)] transition" style="border-color: var(--border);">
                            
                            <td v-for="col in available.columns" class="p-3 text-xs">

                                @{{ record[col.index] }}
                            </td>

                            <!-- Actions -->
                            <td 
                                class="p-3 text-right"
                                v-if="available.actions.length"
                            >
                                <template v-for="(action, index) in record.actions" :key="'actual-' + index"> 
                                    <span
                                        v-if="action?.type !== 'custom'"
                                        class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                        :class="action.icon"
                                        v-text="!action.icon ? action.title : ''"
                                        @click="performAction(action)"
                                    >
                                    </span>
                                </template> 

                                <!-- Custom Actions -->
                                <template v-for="(action, index) in record.actions" :key="'custom-' + index"> 
                                    <span
                                        v-if="action?.type === 'custom'"
                                        class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                        :class="action.icon"
                                        v-text="!action.icon ? action.title : ''"
                                        @click="performCustomActions(action.method, record)"
                                    >
                                    </span>
                                </template>

                            </td>

                        </tr>

                        {{-- Empty --}}
                        <tr v-if="available.records.length === 0">

                            <td :colspan="available.columns.length + available.actions.length"
                                class="p-8 text-center text-sm" style="color: var(--text-muted);">
                                No records found
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

            @if($isPagination)
                <div>
                    <!-- Pagination -->
                    <Paginator
                        :rows="available.meta?.per_page ?? 0"
                        :totalRecords="available.meta?.total ?? 0"
                        @page="onPage"
                        :rowsPerPageOptions="available.meta?.per_page_options ?? [10, 25, 50, 100]"
                        v-if="!isLoading" />
                </div>
            @endif

        </div>
    </div>
</script>

    <script type="module">
        adminVueApp.component('v-quicktable', {
            template: '#v-quicktable-template',

            props: ['available', 'applied', 'isLoading', 'page'],

            inject: {
                customActions: {
                    default: () => {}
                },
            },

            methods: {
                onPage(event) {
                    console.log(event);

                    this.$emit('onChangePagination', event.page, event.rows);
                },

                performCustomActions(fnName, row) {
                    console.log('performCustomActions fn called....');
                    if (typeof this.customActions[fnName] === 'function') {
                        this.customActions[fnName](row);

                        return;
                    }

                    console.error(`Custom action '${fnName}' is not defined.`);
                },

                /**
                 * Perform the specified action.
                 *
                 * @param {object} action
                 * @returns {void}
                 */
                performAction(action) {
                    alert();
                    return;
                    const method = action.method.toLowerCase();

                    switch (method) {
                        case 'get':
                            window.location.href = action.url;

                            break;

                        case 'post':
                        case 'put':
                        case 'patch':
                        case 'delete':
                            this.$emitter.emit('open-confirm-modal', {
                                agree: () => {
                                    this.$axios[method](action.url)
                                        .then(response => {
                                            this.$emitter.emit('add-flash', {
                                                type: 'success',
                                                message: response.data.message
                                            });

                                            this.$emit('actionSuccess', response.data);
                                        })
                                        .catch((error) => {
                                            this.$emitter.emit('add-flash', {
                                                type: 'error',
                                                message: error.response.data.message
                                            });

                                            this.$emit('actionError', error.response.data);
                                        });
                                }
                            });

                            break;

                        default:
                            console.error('Method not supported.');

                            break;
                    }
                },
            }
        });
    </script>
@endPushOnce
