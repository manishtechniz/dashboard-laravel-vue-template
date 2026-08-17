
@if ($attributes->has(':label'))
    <label
        {{ $attributes->merge(['class' => 'active text-(--text-muted)']) }}
        v-text="{{ $attributes->get(':label') }}"
    ></label>
@else
    <label
        {{ $attributes->merge(['class' => 'active text-(--text-muted)']) }}
    > {{ $attributes->get('label') }} </label>
@endif
