@section('title', 'Query question')
<div class="query-question">
    <form wire:submit.prevent="submit" id="questions-from" wire:loading.attr="disabled">
        <input type="text" wire:model.defer="questionIds" class="form-control">
    </form>

    <div class="question--list">
        @foreach ($questions as $question)
            <div class="card p-2 mt-2">
                <p>{{ $question->id }} {!! $question->text !!}</p>

                @foreach ($question->options as $option)
                    <div class="option border {{ $option->is_correct ? 'border-success' : 'border-secondary' }}">
                        {!! $option->text !!}
                    </div>
                @endforeach

                <p>
                    {!! $question->explanation !!}
                </p>
            </div>
        @endforeach
    </div>
</div>
