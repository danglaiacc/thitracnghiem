<nav class="navbar navbar-expand-lg bg-body-tertiary mb-3">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">Home</a>
        <div class="container">
            <div class="d-flex justify-content-between">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{route('exam.index')}}">Exam</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="/">Review mode</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Timed mode</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{route('query-question.index')}}">Query question</a>
                        </li>
                        {{-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Dropdown
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li> --}}
                    </ul>
                </div>
                <button class="btn btn-light btn-dark shadow" id="btnSwitch" onclick="toggleTheme()">
                    <i class="bi bi-brightness-high-fill" id="theme-icon"></i>
                </button>
            </div>
        </div>
    </div>
</nav>
