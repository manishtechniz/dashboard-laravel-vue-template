<v-datagrid-export {{ $attributes }}>
    <div class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800">
        <span class="icon-admin-export text-xl text-gray-600"></span>

        @lang('admin::app.export.export')
    </div>
</v-datagrid-export>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-datagrid-export-template"
    >
        <div>
            <x-admin::modal ref="exportModal">
                <!-- Modal Toggler -->
                <x-slot:toggle>
                    <Button type="button" label="Export"/>
                </x-slot>

                <!-- Modal Header -->
                <x-slot:header>
                    <p class="text-lg! page-title">
                        Download
                    </p>
                </x-slot>

                <!-- Modal Content -->
                <x-slot:content>
                    <x-admin::form action="">
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.control
                                type="select"
                                ::options="options"
                                optionLabel="label"
                                optionValue="code"
                                name="format"
                                v-model="format"
                                appendTo="self"
                            />
                        </x-admin::form.control-group>
                    </x-admin::form>
                </x-slot>

                <!-- Modal Footer -->
                <x-slot:footer>
                    <Button type="button" label="Export" :loading="isLoading" @click="download"/>
                </x-slot>
            </x-admin::modal>
        </div>
    </script>

    <script type="module">
        adminVueApp.component('v-datagrid-export', {
            template: '#v-datagrid-export-template',

            props: ['src'],

            data() {
                return {
                    format: 'xls',

                    isLoading: false,

                    available: null,

                    applied: null,

                    options: [
                        {label: 'CSV', code: 'csv'},
                        {label: 'XLS', code: 'xls'},
                        {label: 'XLSX', code: 'xlsx'},
                    ]
                };
            },

            mounted() {
                this.registerEvents();
            },

            methods: {
                /**
                 * Registers events to update properties and trigger the download process.
                 *
                 * @returns {void}
                 */
                registerEvents() {
                    this.$emitter.on('change-datagrid', this.updateProperties);
                },

                /**
                 * Updates the available and applied properties with new values.
                 *
                 * @param {object} data - Object containing available and applied properties.
                 * @returns {void}
                 */
                updateProperties({ src, available, applied }) {
                    if (this.src !== src) {
                        return;
                    }

                    this.available = available;

                    this.applied = applied;
                },

                /**
                 * Initiates the download process for exporting data.
                 *
                 * @returns {void}
                 */
                download() {
                    if (! this.available?.records?.length) {
                        this.$emitter.emit('add-flash', { type: 'warning', message: 'No Records' });

                        this.$refs.exportModal.toggle();
                    } else {
                        let params = {
                            export: 1,

                            format: this.format,

                            sort: {},

                            filters: {},
                        };

                        if (
                            this.applied.sort.column &&
                            this.applied.sort.order
                        ) {
                            params.sort = this.applied.sort;
                        }

                        this.applied.filters.columns.forEach(column => {
                            params.filters[column.index] = column.value;
                        });

                        this.isLoading = true;

                        this.$axios
                            .get(this.src, {
                                params,
                                responseType: 'blob',
                            })
                            .then((response) => {
                                const url = window.URL.createObjectURL(new Blob([response.data]));

                                /**
                                 * Extracting filename from content-disposition header.
                                 */
                                let filename = `${(Math.random() + 1).toString(36).substring(7)}.${this.format}`;

                                const contentDisposition = response.headers['content-disposition'];

                                if (contentDisposition && contentDisposition.indexOf('attachment') !== -1) {
                                    const filenameMatch = contentDisposition.match(/filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/);

                                    if (filenameMatch != null && filenameMatch[1]) {
                                        filename = filenameMatch[1].replace(/['"]/g, '');
                                    }
                                }

                                /**
                                 * Link generation.
                                 */
                                const link = document.createElement('a');
                                link.href = url;
                                link.setAttribute('download', filename);

                                /**
                                 * Adding a link to a document, clicking on the link, and then removing the link.
                                 */
                                document.body.appendChild(link);
                                link.click();
                                document.body.removeChild(link);

                                this.$refs.exportModal.toggle();
                            }).finally(()=> {
                                this.isLoading = false;
                            })
                    }
                },
            },
        });
    </script>
@endPushOnce
