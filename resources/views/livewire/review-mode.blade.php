<?php
$checkBoxType = $currentQuestion->is_multichoice ? 'checkbox' : 'radio';
?>

<div class="question">
    <h5>Question {{ $currentIndexQuestion + 1 }}:</h5>
    <div class="question--text">
        {!! $currentQuestion->text !!}
    </div>

    <form wire:submit.prevent="submitAnswer">
        <fieldset {{ $isShowExplaination ? 'disabled' : '' }}>

            @foreach ($options as $index => $option)
                <?php
                $border = '';
                if ($isShowExplaination) {
                    if ($option->is_correct) {
                        $border = 'border-success';
                    } elseif (in_array($option->id, $selectedOptions)) {
                        $border = 'border-danger';
                    }
                }
                ?>
                <div class="question--answer-item form-check border border-2 {{ $border }}">
                    <input class="form-check-input" type="{{ $checkBoxType }}" value="{{ $option->id }}"
                        id="answer-{{ $index }}" wire:model="selectedOptions">
                    <label class="form-check-label answer-item--text" for="answer-{{ $index }}">
                        {!! $option->text !!}
                    </label>
                </div>
            @endforeach

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
