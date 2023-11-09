<div class="card">
    <div class="card-body">
        <form wire:submit="save">
            <input type="text" wire:model="text">
            <div>
                @error('text')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit">Save</button>
        </form>
    </div>
</div>
