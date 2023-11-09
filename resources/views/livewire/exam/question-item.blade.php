<form wire:submit="saveQuestion()">
    <input type="text" wire:model="text">
    <div>
        @error('text')
            <span class="error">{{ $message }}</span>
        @enderror
    </div>

    <button type="submit">More</button>
</form>
