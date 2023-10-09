<div>
    <p>
        {!! $currentQuestion->text !!}
    </p>

    <form wire:submit.prevent="checkAnswer">
        @if ($currentQuestion->is_multichoice)
            @foreach ($options as $option)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="{{ $option->id }}" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
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

        <button class="btn btn-success" type="submit">Check</button>
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
