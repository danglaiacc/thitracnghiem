<div>
    {{-- Because she competes with no one, no one can compete with her. --}}
    <h1> {{ $exam->name }} </h1>

    {{-- @foreach ($questions as $questionIndex => $question)
        <p>
            <input type="text" wire:model="questions.{{ $questionIndex }}.text">
        </p>
        <span>
            {{$questions[$questionIndex]->text}}
        </span>

        @foreach ($question->options as $optionIndex => $option)
            <p>
                <input type="text" wire:model="question.options.{{$optionIndex}}.text">
            </p>
        @endforeach
    @endforeach --}}

    @foreach ($questions as $questionIndex => $question)
        <div class="card mt-2">
            <div class="card-header">
                <input type="text" name="questions[{{ $questionIndex }}][title]"
                    wire:model="questions.{{ $questionIndex }}.title" placeholder="Question title">
                    <br>
            </div>
            <div class="card-body">
                @foreach ($question['options'] as $optionIndex => $option)
                    <input type="text" name="questions[{{ $questionIndex }}][options][{{ $optionIndex }}][text]"
                        wire:model="questions.{{ $questionIndex }}.options.{{ $optionIndex }}.text"
                        placeholder="Option text">
                    <input type="checkbox"
                        name="questions[{{ $questionIndex }}][options][{{ $optionIndex }}][is_correct]"
                        wire:model="questions.{{ $questionIndex }}.options.{{ $optionIndex }}.is_correct">
                    <br>
                @endforeach
                <button type="button" wire:click="addOption({{ $questionIndex }})">Add option</button>
            </div>
        </div>
    @endforeach

    <button type="button" wire:click="addQuestion">Add question</button>
    <button type="submit" wire:click="save">Save</button>
</div>
