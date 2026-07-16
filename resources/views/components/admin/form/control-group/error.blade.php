<v-error-message
    {{ $attributes }}
    v-slot="{ message }"
>
    <p
        {{ $attributes->merge(['class' => 'mt-1 text-xs italic text-red-600']) }}
        v-text="message"
    >
    </p>
</v-error-message>
 

