<div>
    <div class="row">
        @foreach ($exams as $exam)
            <div class="col-3 mb-3">
                <div class="card" style="width: 18rem;">
                    <img src="{{ url($exam->thumbnail) }}" class="card-img-top">

                    <div class="card-body">
                        <h5 class="card-title">{{ $exam->name }}</h5>
                        <a href="{{ route('take-exam.review', $exam->uuid) }}" class="card-link">
                            Review mode
                        </a>
                        <a href="{{ route('take-exam.timed', $exam->uuid) }}" class="card-link">
                            Timed mode
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
