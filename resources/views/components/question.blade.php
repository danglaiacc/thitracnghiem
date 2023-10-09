<div class="question">
    {!! $question->text !!}
</div>

<form action="">

    @foreach ($question->options as $option)
        <option value="{{ $option->id }}">
            {!! $option->text !!}
        </option>
    @endforeach

</form>
