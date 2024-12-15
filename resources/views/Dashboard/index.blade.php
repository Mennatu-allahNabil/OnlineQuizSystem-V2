<x-dashboard>
    @section('page_title',"Dashboard")
    @section("css_files")
        <style>
            .custom-pattern {
                position: relative;
                background-color: white;
                /*background: radial-gradient(circle, transparent 20%, #f8f8f8 20%, #fdfdfd 80%, transparent 80%, transparent) 0% 0% / 64px 64px, radial-gradient(circle, transparent 20%, #ffffff 20%, #ffffff 80%, transparent 80%, transparent) 32px 32px / 64px 64px, linear-gradient(#1A8FE5 2px, transparent 2px) 0px -1px / 32px 32px, linear-gradient(90deg, #1A8FE5 2px, #ffffff 2px) -1px 0px / 32px 32px #ffffff;*/
                /*background-size: 64px 64px, 64px 64px, 32px 32px, 32px 32px;*/
                /*background-color: #ffffff;*/
            }
            .blur-layer{
                position: absolute;
                width: 100%;
                height: 100%;
                backdrop-filter: blur(3px);
                z-index: 0;
            }
            .card-body{
                z-index: 1;
            }
            .group:hover svg {
                transform: translateX(0.25rem);
            }
        </style>
    @endsection
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-3 py-2 bg-transparent">
                <div class="card text-black mb-3 custom-pattern shadow-md">
{{--                    <div class="blur-layer"></div>--}}
                    <div class="card-body">
                        <h5 class="card-title fw-bolder">Total Users</h5>
                        <p class="card-text display-5 fw-bolder">{{ $totalUsers }}</p>
                        <a href="{{ route('allusers.showall') }}" class="inline-block group">
                            <button class="btn btn-primary relative overflow-hidden">
                                <span class="flex items-center">
                                    View All Users
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </span>
                            </button>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 py-2 bg-transparent">
                <div class="card text-black  mb-3 custom-pattern shadow-md">
{{--                    <div class="blur-layer"></div>--}}

                    <div class="card-body">
                        <h5 class="card-title fw-bolder">Total Quiz Takers</h5>
                        <p class="card-text display-5 fw-bolder">{{ $totalTakers }}</p>
                        <a href="{{ route('users.showall') }}" class="inline-block group">
                            <button class="btn btn-primary relative overflow-hidden">
                                <span class="flex items-center">
                                    View All Quiz Takers
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </span>
                            </button>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 py-2 bg-transparent">
                <div class="card text-black  mb-3 custom-pattern shadow-md">
{{--                    <div class="blur-layer"></div>--}}

                    <div class="card-body">
                        <h5 class="card-title fw-bolder">Total Administrators</h5>
                        <p class="card-text display-5 fw-bolder">
                            {{ $totalAdmins }}</p>
                        <a href="{{ route('admins.showall') }}" class="inline-block group">
                            <button class="btn btn-primary relative overflow-hidden">
                                <span class="flex items-center">
                                    View Admins
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </span>
                            </button>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <h4 class="fw-bold mb-4">Recently Added Quizzes</h4>
                <div class="list-group">
                    @forelse ($recentQuizzes as $quiz)
                        <a href="{{ route('questions.index',$quiz->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1 fw-bold">{{ $quiz->title }}</h6>
                                <p class="mb-0 text-muted">Created on {{ $quiz->created_at->format('F j, Y') }}</p>
                            </div>
                            <span class="badge bg-primary px-5 py-3 fs-6"> View </span>
                        </a>
                    @empty
                        <div class="alert alert-info" role="alert">
                            No quizzes added recently.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-dashboard>
