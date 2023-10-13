<?php
use App\Enums\ResultMessage;
$checkBoxType = $currentQuestion['is_multichoice'] ? 'checkbox' : 'radio';
?>
@section('title', $exam->name)

<div class="question">
    <div class="d-flex justify-content-between">
        <h2>{{ $exam->name }}</h2>
        <div class="d-flex align-items-center">
            <a class="nav-link disabled" aria-disabled="true">
                <p id="demo" data-time="{{ $exam->time }}" class="mb-0"></p>
            </a>

            <div class="btn btn-secondary" wire:click.prevent='saveExamResult'>
                <i class="bi bi-pause-circle-fill"></i>
            </div>
            <div class="rounded-circle bg-success d-flex align-items-center justify-content-center"
                style="width:50px;height:50px;">
                {{ $totalCorrectAnswer }}
            </div>
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
                    {{-- {{ json_encode($currentQuestion['user_answers'])}} --}}
                    <input class="form-check-input" type="{{ $checkBoxType }}" value={{ $option['id'] }}
                        id="answer-{{ $index }}" wire:model.defer="selectedOptions">

                    <label class="form-check-label answer-item--text" for="answer-{{ $index }}">
                        {!! $option['text'] !!}
                    </label>
                </div>
            @endforeach

        </fieldset>

        <div class="d-flex justify-content-between">
            <div>
                @if ($currentQuestionIndex > 0)
                    <button class="btn btn-warning" wire:click.prevent="loadQuestion({{ $currentQuestionIndex - 1 }})">
                        Previous
                    </button>
                @endif
            </div>
            <div>
                <button class="btn btn-warning" wire:click.prevent="loadQuestion({{ $currentQuestionIndex - 1 }})">
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
                <button class="btn btn-primary" wire:click.prevent="loadQuestion({{ $currentQuestionIndex + 1 }})">
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
            <div class="p-3 mt-2 bg-success text-white">
                {{ ResultMessage::CORRECT_ANSWER }}
            </div>
        @else
            <div class="p-3 mt-2 bg-danger text-white">
                {{ ResultMessage::IN_CORRECT_ANSWER }}
            </div>
        @endif

        <p>
            {!! $currentQuestion['explaination'] !!}
        </p>
        <div style="position:sticky;bottom:5px;right:5px;margin:0;padding:5px 3px;" class="d-flex justify-content-end">
            <button class="btn btn-danger" wire:click.prevent="addToHardQuestion">Add to hard</button>
            <button class="btn btn-primary ms-2" wire:click.prevent="nextQuestion">Next</button>
        </div>
    @endif

</div>

@push('js')
    <script type="text/javascript">
        // window.onbeforeunload = function() {
        //     return "Refresh page? Are you sure haha?";
        // }

        // ============== count down timer
        // Set the date we're counting down to
        const currentDate = new Date();
        var timeItem = +document.getElementById("demo").getAttribute('data-time');
        var countDownDate = new Date(currentDate.getTime() + timeItem * 1000).getTime();

        // Update the count down every 1 second
        var x = setInterval(function() {

            // Get today's date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            // var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the result in the element with id="demo"
            document.getElementById("demo").innerHTML = hours + "h " +
                minutes + "m " + seconds + "s ";

            // If the count down is finished, write some text
            if (distance < 0) {
                clearInterval(x);
                document.getElementById("demo").innerHTML = "EXPIRED";
            }
        }, 1000);
    </script>
@endpush
