<?php
use App\Enums\DbStatus;
?>
<div class="exam upsert">
    <h1> {{ $exam->name }} </h1>

    <div class="question--card">
        <form wire:submit="saveExam" id="questions-from" wire:loading.attr="disabled">
            @foreach ($questions as $questionIndex => $question)
                @if ($question['db_status'] == DbStatus::DELETE)
                    @continue
                @endif

                <div class="card mt-2">
                    <div class="card-header">
                        <textarea rows="1" type="text" class="form-control" wire:model="questions.{{ $questionIndex }}.text"
                            placeholder="Question text"> </textarea>

                        <textarea rows="1" type="text" class="form-control mt-2"
                            wire:model="questions.{{ $questionIndex }}.explaination" placeholder="Explaination text"> </textarea>
                    </div>
                    <div class="card-body">
                        @foreach ($question['options'] as $optionIndex => $option)
                            <div class="d-flex align-items-center mb-2">
                                <input type="checkbox" class="form-check-input me-2"
                                    wire:model="questions.{{ $questionIndex }}.options.{{ $optionIndex }}.is_correct"
                                    {{ $option['is_correct'] == 1 ? 'checked' : '' }}>
                                <textarea rows="1" type="text" wire:model="questions.{{ $questionIndex }}.options.{{ $optionIndex }}.text"
                                    placeholder="Option text" class="form-control"> </textarea>
                            </div>
                        @endforeach
                        <div class="d-flex question--actions">

                            <button type="button" class="btn btn-primary"
                                wire:click="addOptionClick({{ $questionIndex }})">
                                Add option
                            </button>
                            <button type="button" class="btn btn-danger"
                                wire:click="removeQuestionClick('{{ $question['uuid'] }}', {{ $questionIndex }})">
                                Remove question
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </form>

    </div>

    <div class="fixed-bottom question--card--action d-flex">

        <div>
            @if (session()->has('updateExamMessage'))
                <div class="alert alert-success p-1 m-0 bg-transparent">
                    {{ session('updateExamMessage') }}
                </div>
            @endif
        </div>

        <div class="ms-auto">
            <button type="button" class="btn btn-warning" wire:click="addQuestionClick">
                Add question
            </button>
            <button type="submit" form="questions-from" class="btn btn-success">
                Save
            </button>
        </div>
    </div>
</div>
