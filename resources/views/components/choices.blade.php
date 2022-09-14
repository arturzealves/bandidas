<div
    x-data="{
        model: @entangle($attributes->wire('model')),
    }"
    x-init="
        select2 = new Choices($refs.select, {
            position: 'bottom',
            searchPlaceholderValue: '{{__('Search...')}}',
            shouldSort: false,
            itemSelectText: '',
            removeItems: true,
            removeItemButton: true,
            allowHTML: false,
        });

        $refs.select.addEventListener('change', (event) => {
            if (event.target.hasAttribute('multiple')){
                model = Array.from(event.target.options).filter(option => option.selected).map(option => option.value);
            } else {
                model = event.target.value;
            }
        });
    "
    wire:ignore
>
    <select x-ref="select" {{ $attributes->merge(['class' => '']) }}>
        {{ $slot }}
    </select>
</div>
