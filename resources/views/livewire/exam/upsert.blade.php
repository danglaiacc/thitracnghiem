<div class="exam upsert">
    <h1> {{ $exam->name }} </h1>

    <div class="questions--card">
        @foreach ($questions as $questionIndex => $question)
            <div class="card mt-2">
                <div class="card-header">
                    <textarea rows="1" type="text" class="form-control" wire:model="questions.{{ $questionIndex }}.text"
                        placeholder="Question text">
                    </textarea>

                    <textarea rows="1" type="text" class="form-control mt-2" wire:model="questions.{{ $questionIndex }}.explaination"
                        placeholder="Explaination text">
                    </textarea>
                </div>
                <div class="card-body">
                    @foreach ($question['options'] as $optionIndex => $option)
                        <div class="d-flex align-items-center mb-2">
                            <input type="checkbox" class="form-check-input me-2"
                                wire:model="questions.{{ $questionIndex }}.options.{{ $optionIndex }}.is_correct">
                            <textarea rows="1" type="text" wire:model="questions.{{ $questionIndex }}.options.{{ $optionIndex }}.text"
                                placeholder="Option text" class="form-control">
                            </textarea>
                        </div>
                    @endforeach
                    <button type="button" class="btn btn-primary" wire:click="addOption({{ $questionIndex }})">
                        Add option
                    </button>
                </div>
            </div>
        @endforeach

    </div>
    <div class="fixed-bottom" style="left: unset;">
        <button type="button" class="btn btn-warning" wire:click="addQuestion">
            Add question
        </button>
        <button type="submit" class="btn btn-success" wire:click="save">
            Save
        </button>
    </div>
</div>
