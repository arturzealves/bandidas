<div>
    <dl>
        <dt>Artists</dt>
        <dd>
            <x-choices id="filter-student-status" wire:model.defer="selected" multiple wire:change="update">
                @if(!empty($artists))
                    @foreach($artists as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                @endif
            </x-select2>
        </dd>
        <dt>Budget</dt>
        <dd>
            <input type="number" wire:model.lazy="budget" 
                step="1" min="0" wire:change="updateBudget" value="{{ $budget }}"
                class="w-full"
            />
        </dd>
    </dl>
</div>
