<div>
    <h3>Question {{ $currentIndexQuestion + 1 }}:</h3>
    <p>
        {!! $currentQuestion->text !!}
    </p>

    <form wire:submit.prevent="checkAnswer">
        <fieldset {{ $isShowExplaination ? 'disabled' : '' }}>

            @if ($currentQuestion->is_multichoice)
                @foreach ($options as $option)
                    <div class="form-check border border-2">
                        <input class="form-check-input" type="checkbox" value="{{ $option->id }}"
                            id="answer-{{ $option->id }}">
                        <label class="form-check-label" for="answer-{{ $option->id }}">
                            {!! $option->text !!}
                        </label>
                    </div>
                @endforeach
            @else
                @foreach ($options as $option)
                    <div class="form-check">
                        <input wire:model="selectedOptions" class="form-check-input" type="radio"
                            id="ans-{{ $option->id }}" value="{{ $option->id }}">
                        <label class="form-check-label" for="ans-{{ $option->id }}">
                            {!! $option->text !!}
                        </label>
                    </div>
                @endforeach
            @endif

        </fieldset>
        <button class="btn btn-success" type="submit">Check</button>
        <button class="btn btn-warning" wire:click.prevent="previousQuestion">Previous</button>
        <button class="btn btn-primary" wire:click.prevent="nextQuestion">Next</button>
        <button class="btn btn-danger" wire:click.prevent="addToHardQuestion">Add to hard</button>
    </form>

    @if ($isShowExplaination)
        @if ($isCorrectAnswer)
            <div class="p-3 mt-2 bg-success text-white">Correct</div>
        @else
            <div class="p-3 mt-2 bg-danger text-white">Incorrect</div>
        @endif

        <p>
            {!! $currentQuestion->explaination !!}
        </p>
    @endif
</div>
