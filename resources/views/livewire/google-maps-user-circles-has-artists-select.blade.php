<div>
    <x-choices id="filter-student-status" wire:model.defer="selected" multiple wire:change="update">
        @if(!empty($artists))
            @foreach($artists as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
            @endforeach
        @endif
    </x-select2>
    <br>

    {{ __('Budget') }}:
    <input type="number" wire:model.lazy="budget" step="0.01" min="0" wire:change="updateBudget" value="{{ $budget }}"/>
    <br>
</div>
