<?php
$checkBoxType = $currentQuestion['is_multichoice'] ? 'checkbox' : 'radio';
?>
@section('title', $exam->name)
@section('time', $exam->time)

<div class="question">
    <div class="d-flex justify-content-between">
        <h2>{{ $exam->name }}</h2>
        <div>
            <button type="button" class="btn btn-primary position-relative">
                Correct answer
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ $totalCorrectAnswer }}
                </span>
            </button>

            {{-- <button class="btn btn-warning" wire:click.prevent="finishExam">
                Finish
            </button> --}}
        </div>
    </div>

    <h5 class="{{ $isShowExplaination ? ($isCorrectAnswer ? 'text-success' : 'text-danger') : '' }}">
        Question {{ $currentQuestionIndex + 1 }} / {{ $totalQuestion }}
    </h5>
    <div class="question-text">
        {!! $currentQuestion['text'] !!}
    </div>

    <form wire:submit.prevent="submitAnswer" wire:key="{{ $currentQuestionIndex }}">
        <fieldset {{ $isShowExplaination ? 'disabled' : '' }}>

            @foreach ($currentQuestion['options'] as $index => $option)
                <?php
                $border = '';
                if ($isShowExplaination) {
                    if ($option['is_correct']) {
                        $border = 'border-success';
                    } elseif (in_array($option['id'], $selectedOptions)) {
                        $border = 'border-danger';
                    }
                }
                
                ?>
                <div class="question--answer-item form-check border border-2 {{ $border }}">
                    <input class="form-check-input" type="{{ $checkBoxType }}" value={{ $option['id'] }}
                        id="answer-{{ $index }}" wire:model.defer="selectedOptions">

                    <label class="form-check-label answer-item--text" for="answer-{{ $index }}">
                        {!! $option['text'] !!} {{ $option['id'] }}
                        {{ json_encode($currentQuestion['user_answers']) }}
                    </label>
                </div>
            @endforeach

        </fieldset>

        <div class="d-flex justify-content-between">
            <div>
                @if ($currentQuestionIndex > 0)
                    <button class="btn btn-warning" wire:click="previousQuestion">
                        Previous
                    </button>
                @endif
            </div>
            <div>
                <button class="btn btn-warning" wire:click.prevent="previousQuestion">
                    Previous
                </button>
                <button class="btn btn-danger" wire:click.prevent="addToHardQuestion">
                    Add to hard
                </button>
                @if (!$isShowExplaination)
                    <button class="btn btn-success" type="submit">
                        Check
                    </button>
                @endif
                <button class="btn btn-primary" wire:click.prevent="nextQuestion">
                    Next
                </button>
            </div>
        </div>
    </form>

    @if (session()->has('message'))
        <div class="alert alert-{{ session('background') }} mt-2 p-2">
            {{ session('message') }}
        </div>
    @endif

    @if ($isShowExplaination)
        @if ($isCorrectAnswer)
            <div class="p-3 mt-2 bg-success text-white">Correct</div>
        @else
            <div class="p-3 mt-2 bg-danger text-white">Incorrect</div>
        @endif

        <p>
            {!! $currentQuestion->explaination !!}
        </p>

        <div style="position:sticky;bottom:5px;right:5px;margin:0;padding:5px 3px;" class="d-flex justify-content-end">
            <button class="btn btn-danger" wire:click.prevent="addToHardQuestion">Add to hard</button>
            <button class="btn btn-primary ms-2" wire:click.prevent="nextQuestion">Next</button>
        </div>
    @endif

</div>

{{-- @push('js')
    <script type="text/javascript">
        window.onbeforeunload = function() {
            return "Refresh page? Are you sure haha?";
        }
    </script>
@endpush --}}
