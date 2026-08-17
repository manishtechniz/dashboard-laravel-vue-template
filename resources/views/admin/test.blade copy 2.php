<x-admin::layouts>
<v-test></v-test>
@pushOnce('scripts')

<script
    type="text/x-template"
    id="v-test-template"
>
  {{-- <Form v-slot="$form" :initialValues  @submit="onFormSubmit" class="flex flex-col gap-4 w-full sm:w-56">
    <div class="flex flex-col gap-1">
        <InputText name="username" type="text" placeholder="Username" fluid rules="required" />
    </div>
    <div class="flex flex-col gap-1">
        <InputText name="firstName" type="text" placeholder="First Name" fluid :formControl="{ validateOnValueUpdate: true }" />
    </div>

    <FloatLabel variant="in">
    <InputText id="in_label" v-model="value1" autocomplete="off" />
    <label for="in_label">In Label</label>
</FloatLabel>

    <div class="flex flex-col gap-1">

    </div>
    <Button type="submit"  label="Submit" />
</Form> --}}

<x-admin::form :action="''" method="GET" class="flex flex-col gap-4 w-full sm:w-56 mt-5!">
    <!-- Email -->
    {{-- <x-admin::form.control-group>
        <x-admin::form.control-group.control
            type="textarea"
            class=""
            id="email"
            name="email"
            rules="required|email"
            :placeholder="'Enter your place'"

            label="This label"
        />
    </x-admin::form.control-group> --}}

       <!-- Text -->
    <x-admin::form.control-group>
        <x-admin::form.control-group.control
            type="text"
            class=""
            id="name"
            name="name"
            rules="required"
            :placeholder="'Enter your place'"
            v-model='selectedCountry'
            ::label="@{{ testName }}"
        />
    </x-admin::form.control-group>

    {{-- <x-admin::form.control-group>
        <x-admin::form.control-group.control
            type="date"
            class=""
            id="date"
            name="date"
            rules="required"
            :placeholder="'Enter your place'"
            label="date"
        />
    </x-admin::form.control-group> --}}

    @{{ test2 }}
    {{-- <x-admin::form.control-group>
        <x-admin::form.control-group.control
            type="select"
            ::options="cities"
            optionLabel="name"
            optionValue="code"
            name="select"
            label="select"
            v-model="test2"

            @change="testChange"
        />
    </x-admin::form.control-group> --}}

    {{-- <x-admin::form.control-group>
        <x-admin::form.control-group.control
            type="multiselect"
            class=""
            ::options="cities"
            optionLabel="name"
            optionValue="code"
            name="multiselect"
            :placeholder="'Placeholder'"
            rules="required"
            v-model="selectedCountry"
            label="multiselect"
        />
    </x-admin::form.control-group> --}}

    <!-- Checkbox -->
    {{-- <x-admin::form.control-group>
        <div class="flex gap-2">
            <x-admin::form.control-group.control
                type="checkbox"
                name="checkbox"
                inputId="checkboxVal"
                for="checkboxVal"
                label="Checkbox"
                value="Cheese"
                rules="required"
                {{-- v-model='checkbox' --}}
                checked=""
                {{-- @change="checkValue" --}}
            />
        </div>

        <x-admin::form.control-group.error control-name="checkbox" />
    </x-admin::form.control-group> --}}

    <!-- Switch -->
    {{-- <x-admin::form.control-group>
        <div class="flex gap-2">
            <x-admin::form.control-group.control
                type="switch"
                name="kya_hua"
                inputId="switchId"
                for="switchId"
                value="Cheese"
                label="Swicth"
                rules="required"
                v-model='v_switch'
                ::checked="v_switch"
                @change="checkValue"
            />

            {{-- <x-admin::form.control-group.label label="Checkbox" /> --}}
        </div>

        <x-admin::form.control-group.error control-name="switchh" />
    </x-admin::form.control-group> --}}

    <!-- Radio -->
    {{-- <x-admin::form.control-group>
        <x-admin::form.control-group.control
            type="radio"
            name="radio"
            ::inputId="'radio1'"
            ::for="'radio1'"
            value="Radio1"
            rules="required"
            label="Radio1"
            v-model='radio'
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
            v-model='radio'
            checked=""
            @change="checkValue"
        />

        <x-admin::form.control-group.error control-name="radio" />
    </x-admin::form.control-group> --}}

        {{-- <x-admin::form.control-group>
            <x-admin::form.control-group.control
                type="custom"
                name="customm"
                label="Custom Select"
                rules="required"
            >
                <Select :options="countries" filter optionLabel="name" placeholder="Select a Country" class="w-full md:w-56">
                    <template #value="slotProps">
                        <div v-if="slotProps.value" class="flex items-center">
                            <img ::alt="slotProps.value.label" src="https://primefaces.org/cdn/primevue/images/flag/flag_placeholder.png"
                            ::class="`mr-2 flag flag-${slotProps.value.code.toLowerCase()}`" style="width: 18px" />
                            <div>@{{ slotProps.value.name }}</div>
                        </div>
                        <span v-else>
                            @{{ slotProps.placeholder }}
                        </span>
                    </template>
                    <template #option="slotProps">
                        <div class="flex items-center">
                            <img ::alt="slotProps.option.label" src="https://primefaces.org/cdn/primevue/images/flag/flag_placeholder.png" ::class="`mr-2 flag flag-${slotProps.option.code.toLowerCase()}`" style="width: 18px" />
                            <div>@{{ slotProps.option.name }}</div>
                        </div>
                    </template>
                </Select>
            </x-admin::form.control-group.control>
        </x-admin::form.control-group> --}}

        <Textarea name="new_textarea" rows="5" cols="30" />


        <Button type="submit" label="Save" :loading="false"   />
        <Button type="button" label="Toast Me" :loading="false"  @click="show" />

</x-admin::form>

</script>

<script type="module">
    adminVueApp.component('v-test', {
        template: '#v-test-template',

        data() {
             return {
                testName:"TestNameHello",
                selectedCountry: null,
                test2: null,
                checkbox: false,
                radio: null,
                v_switch: false,
                initialValues: {
                    username: '',
                    firstName: '',
                    lastName: '',
                },
                email: 'test@gmail.com',

                dynamicsFields: {},

                cities: [
                    { name: 'None', code: '' },
                    { name: 'New York', code: 'NY' },
                    { name: 'Rome', code: 'RM' },
                    { name: 'London', code: 'LDN' },
                    { name: 'Istanbul', code: 'IST' },
                    { name: 'Paris', code: 'PRS' }
                ],

                countries: [
                    { name: 'None', code: '' },
                    { name: 'Australia', code: 'AU' },
                    { name: 'Brazil', code: 'BR' },
                    { name: 'China', code: 'CN' },
                    { name: 'Egypt', code: 'EG' },
                    { name: 'France', code: 'FR' },
                    { name: 'Germany', code: 'DE' },
                    { name: 'India', code: 'IN' },
                    { name: 'Japan', code: 'JP' },
                    { name: 'Spain', code: 'ES' },
                    { name: 'United States', code: 'US' }
                ]
            };
        },
        mounted() {
            // alert();
            // this.$emitter.emit('add-flash', { type: 'success', message: "Test Message" });
            // this.$toast.add({ severity: 'info', summary: 'Info', detail: 'Message Content', life: 3000 });
        },


        methods: {
            show() {
                    // this.$toast.add({ severity: 'info', detail: 'Message Content' });
                },

            addFilter($event, col) {
                console.log("Hello",$event.target.value);
                // $event.target.value = '';
                console.log(this.dynamicsFields);
                this.dynamicsFields[col] = '';

                console.log(this.dynamicsFields);
                console.log("Hello2",$event.target.value);


            },

            testChange($event) {
                // alert();

                console.log($event.value);
            },

            checkValue() {
                // console.log('Name: ById', document.getElementById('name').value);
                // console.log('Name: By Nmae: ', document.getElementsByName('name')[0].value);

                // console.log('Checkbox: ByName: ', !! document.getElementsByName('checkbox')[0].value? 'True' :'False');
                console.log('Checkbox: ById', document.getElementById('checkboxVal').value);
                console.log('v-Checkbox: ', this.v_switch);
            }
        }

    });
</script>

@endPushOnce
</x-admin::layouts>
