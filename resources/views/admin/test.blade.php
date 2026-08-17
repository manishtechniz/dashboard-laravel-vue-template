<x-admin::layouts>
    <v-test></v-test>

    @pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-test-template">
        <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form @submit="handleSubmit($event, create)">

                    <!-- Email -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.control
                            type="email"
                            class=""
                            id="email"
                            ::name="emailName"
                            rules="required|email"
                            :placeholder="'Enter your email'"
                            label="This label"
                        />
                
                    </x-admin::form.control-group>

                    <!-- Text -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.control
                            type="text"
                            class=""
                            id="name"
                            name="name"
                            rules="required"
                            :placeholder="'Enter your name'" 
                            ::label="testName"
                        />
                    </x-admin::form.control-group>

                    <!-- Description -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.control
                            type="textarea"
                            class=""
                            id="description"
                            name="description"
                            rules="required"
                            :placeholder="'Descriptin'"
                            label="This label"
                        />
                    </x-admin::form.control-group>
                    
                    <!-- Date -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.control
                            type="date"
                            class=""
                            id="date"
                            name="date"
                            rules="required"
                            :placeholder="'Enter your place'"
                            label="date" 
                        />
                    </x-admin::form.control-group>

                    <!-- Select -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.control
                            type="select"
                            ::options="cities"
                            optionLabel="name"
                            rules="required"
                            optionValue="code"
                            name="select"
                            label="select" 
                        />
                    </x-admin::form.control-group>

                    <!-- File -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label label="file" />
                            
                        <x-admin::form.control-group.control
                            type="file"
                            rules="required"
                            name="file"
                            label="file" 
                        />
                    </x-admin::form.control-group>

                    <!-- MultiSelect -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.control
                            type="multiselect"
                            class=""
                            ::options="cities"
                            optionLabel="name"
                            optionValue="code"
                            name="multiselect"
                            :placeholder="'Placeholder'"
                            rules="required" 
                            label="multiselect"
                        />
                    </x-admin::form.control-group>

                    <!-- Checkbox -->
                    <x-admin::form.control-group>
                        <div class="flex gap-2">
                            <x-admin::form.control-group.control
                                type="checkbox"
                                name="checkbox"
                                inputId="checkboxVal"
                                for="checkboxVal"
                                label="Checkbox"
                                value="Cheese"
                                rules="required"
                                checked=""
                            />
                        </div>

                        <x-admin::form.control-group.error name="checkbox" />
                    </x-admin::form.control-group>

                    <!-- Switch -->
                    <x-admin::form.control-group>
                        <div class="flex gap-2">
                            <x-admin::form.control-group.control
                                type="switch"
                                name="kya_hua"
                                inputId="switchId"
                                for="switchId"
                                value="Cheese"
                                label="Swicth"
                                rules="required"
                                ::checked="v_switch"
                                @change="checkValue"
                            />

                            <x-admin::form.control-group.label label="Switch" />
                        </div>

                        <x-admin::form.control-group.error name="switchh" />
                    </x-admin::form.control-group>

                    <!-- Radio -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.control
                            type="radio"
                            name="radio"
                            ::inputId="'radio1'"
                            ::for="'radio1'"
                            value="Radio1"
                            rules="required"
                            label="Radio1"
                            checked=""
                            @change="checkValue"
                        />

                        <x-admin::form.control-group.control
                            type="radio"
                            name="radio"
                            inputId="radio2"
                            for="radio2"
                            value="radio2"
                            label="Radio2"
                            rules="required" 
                            checked=""
                            @change="checkValue"
                        />

                        <x-admin::form.control-group.error name="radio" />
                    </x-admin::form.control-group>

                     <Button type="submit" label="Save" :loading="false"   />

                </form>

               
                <Button type="button" label="Toast Me" :loading="false"  @click="show" />

            </x-admin::form>
        </script>

    <script type="module">
        adminVueApp.component('v-test', {
            template: '#v-test-template',

            data() {
                return {
                    emailName: 'email',

                    cities: [{
                            name: 'None',
                            code: ''
                        },
                        {
                            name: 'New York',
                            code: 'NY'
                        },
                        {
                            name: 'Rome',
                            code: 'RM'
                        },
                        {
                            name: 'London',
                            code: 'LDN'
                        },
                        {
                            name: 'Istanbul',
                            code: 'IST'
                        },
                        {
                            name: 'Paris',
                            code: 'PRS'
                        }
                    ],

                    countries: [{
                            name: 'None',
                            code: ''
                        },
                        {
                            name: 'Australia',
                            code: 'AU'
                        },
                        {
                            name: 'Brazil',
                            code: 'BR'
                        },
                        {
                            name: 'China',
                            code: 'CN'
                        },
                        {
                            name: 'Egypt',
                            code: 'EG'
                        },
                        {
                            name: 'France',
                            code: 'FR'
                        },
                        {
                            name: 'Germany',
                            code: 'DE'
                        },
                        {
                            name: 'India',
                            code: 'IN'
                        },
                        {
                            name: 'Japan',
                            code: 'JP'
                        },
                        {
                            name: 'Spain',
                            code: 'ES'
                        },
                        {
                            name: 'United States',
                            code: 'US'
                        }
                    ]
                };
            },
            mounted() {},

            methods: {
                show() {
                    // this.$toast.add({ severity: 'info', detail: 'Message Content' });
                },
                addFilter($event, col) {
                    this.dynamicsFields[col] = '';
                },

                testChange($event) {},

                checkValue() {},

                create(params, {
                    resetForm,
                    setErrors
                }) {
                    this.isLoading = true;

                    this.$axios.post("", params)
                        .then((response) => {

                            resetForm();
                        })
                        .catch(error => {
                            this.isLoading = false;

                            if (error.response.status == 422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                }
            }
        });
    </script>
    @endPushOnce
</x-admin::layouts>