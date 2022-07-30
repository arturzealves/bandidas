<x-jet-form-section submit="updateUserType">

    <x-slot name="title">
        {{ __('User type') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your user account to a normal, promotor or artist account.') }}
    </x-slot>

    <x-slot name="form">

        <!-- UserType -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="user_type" value="{{ __('Name') }}" />
            <select wire:model="userType" wire:change="$emit('postChanged')">
                @foreach ($userTypes as $userType)
                    <option value="{{ $userType->id }}">{{ $userType->name }}</option>
                @endforeach
            </select>
            <x-jet-input-error for="user_type" class="mt-2" />
        </div>
    </x-slot>
</x-jet-form-section>
