@section('title', 'Thi trắc nghiệm')

<div>
    <div class="row">
        @foreach ($exams as $exam)
            <?php $examUrl = route('take-exam.timed', $exam->uuid); ?>

            <div class="col-3 mb-3" href="{{ $examUrl }}">
                <div class="card exam-card" style="width: 18rem;">
                    <a href="{{ $examUrl }}">
                        <img src="{{ url($exam->thumbnail) }}" class="card-img-top">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">{{ $exam->name }}</h5>
                        <a href="{{ $examUrl }}" class="card-link">
                            Review mode
                        </a>
                        <a href="{{}}" class="card-link">
                            Timed mode
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
