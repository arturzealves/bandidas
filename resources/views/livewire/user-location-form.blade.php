<div>
    <form wire:submit.prevent="submit" id="userLocationForm">
        <input type="hidden" wire:model.lazy="latitude" name="latitude" />
        <input type="hidden" wire:model.lazy="longitude" name="longitude" />
    </form>
</div>
