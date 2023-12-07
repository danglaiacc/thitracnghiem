<?php
use App\Enums\ResultMessage;
$checkBoxType = $currentQuestion['is_multichoice'] ? 'checkbox' : 'radio';
?>
@section('title', $exam->name)

<div class="take-exam">
    <div class="d-flex justify-content-between">
        <h2>{{ $exam->name }}</h2>
        <div class="d-flex align-items-center">
            <a wire:ignore class="nav-link disabled" aria-disabled="true">
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

    <div class="question-cards">
        @for ($i = 0; $i < $totalQuestion; $i++)
            <?php
            // $background = $currentQuestionIndex == $i ? 'bg-primary' : '';
            $background = '';
            if ($i == $currentQuestionIndex) {
                $background = 'bg-primary';
            } elseif ($questions[$i]['is_submit']) {
                if ($questions[$i]['is_submit_correct']) {
                    $background = 'bg-success';
                } else {
                    $background = 'bg-danger';
                }
            }
            ?>

            <button class="btn border-0 btn-secondary rounded-circle {{ $background }}"
                wire:click.prevent="loadQuestion({{ $i }})">
                {{ $i + 1 }}
                @if ($questions[$i]['is_review'])
                    <i class="is-review bi bi-star-fill text-warning" wire:click="setReview(false)"></i>
                @endif
            </button>
        @endfor
    </div>
    <div class="question--number d-flex align-items-center my-2">
        <h5
            class="d-inline-block mb-0 {{ $isShowExplanation ? ($questions[$currentQuestionIndex]['is_submit_correct'] ? 'text-success' : 'text-danger') : '' }}">
            Question {{ $currentQuestionIndex + 1 }} / {{ $totalQuestion }}
            {{$currentQuestion['id']}}
        </h5>

        <a href="#" class="pe-auto ms-2">
            @if ($currentQuestion['is_review'])
                <i class="bi bi-star-fill text-warning" wire:click="setReview(false)"></i>
            @else
                <i class="bi bi-star" wire:click="setReview(true)"></i>
            @endif
        </a>
    </div>

    <div class="question-text">
        {!! $currentQuestion['text'] !!}
    </div>

    <form id="form--answer" wire:submit.prevent="submitAnswer" wire:key="{{ $currentQuestionIndex }}">
        <fieldset {{ $isShowExplanation ? 'disabled' : '' }}>

            @foreach ($currentQuestion['options'] as $index => $option)
                <?php
                $border = '';
                if ($isShowExplanation) {
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

    </form>

    <div class="d-flex justify-content-between form-button sticky-top p-2">
        <div>
            @if ($currentQuestionIndex > 0)
                <button class="btn btn-warning" wire:click.prevent="loadQuestion({{ $currentQuestionIndex - 1 }})">
                    Previous
                </button>
            @endif
        </div>
        <div>
            <button class="btn btn-danger" wire:click.prevent="addToHardQuestion">
                Add to hard
            </button>
            @if (!$isShowExplanation)
                <button class="btn btn-success" type="submit" form="form--answer">
                    Check
                </button>
            @endif
            @if ($currentQuestionIndex + 1 == $totalQuestion)
                <button class="btn btn-primary ms-2" wire:click.prevent="saveExamResult(true)">
                    Finish
                </button>
            @else
                <button class="btn btn-primary ms-2"
                    wire:click.prevent="loadQuestion({{ $currentQuestionIndex + 1 }})">
                    Next
                </button>
            @endif
        </div>
    </div>
    @if (session()->has('message'))
        <div class="alert alert-{{ session('background') }} mt-2 p-2">
            {{ session('message') }}
        </div>
    @endif

    @if ($isShowExplanation)
        <div class="explanation" target="_blank">
            @if ($questions[$currentQuestionIndex]['is_submit_correct'])
                <div class="p-3 mt-2 bg-success text-white">
                    {{ ResultMessage::CORRECT_ANSWER }}
                </div>
            @else
                <div class="p-3 mt-2 bg-danger text-white">
                    {{ ResultMessage::IN_CORRECT_ANSWER }}
                </div>
            @endif

            <p>
                {!! $currentQuestion['explanation'] !!}
            </p>
        </div>
    @endif

</div>

@push('js')
    <script type="text/javascript">
        window.onbeforeunload = function() {
            return "Refresh page? Are you sure haha?";
        }

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
