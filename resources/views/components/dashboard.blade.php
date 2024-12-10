<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page_title')</title>
    <link rel="icon" type="image/svg" href="{{ asset('favicon.svg') }}">


        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
{{--    <link href="{{ asset('assets/icons/font/bootstrap-icons.css') }}" rel="stylesheet">--}}
    <link href="{{ asset('assets/css/dashboard.css') }}" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">

    <style>
        html{
            scroll-behavior: smooth;
        }
        .go-up-down{
            display: flex;
            flex-direction: column;
            text-align: center;
            width: 2em;
            gap: 0.25rem;
            position: fixed;
            bottom: 1em;
            right: 1em;
            z-index: 1000;
        }
        #go-up,
        #go-down{
            margin-block: 0.25em;
            width: 1.75em;
            height: 1.75em;
            color: white;
            background-color: #0a58ca;
            border-radius: 50%;
            text-align: center;
            line-height: 2;
        }

    </style>
@yield("css_files")


</head>



<body>

    <div class="go-up-down">
        <button type="button" id="go-up"><i class="fas fa-chevron-up"></i></button>
        <button type="button" id="go-down"><i class="fas fa-chevron-down"></i></button>
    </div>


    @include('sweetalert::alert')

    <div class="main-container d-flex ">
        <div class="sidebar" id="side_nav">
            <div class="header-box px-2 pt-3 pb-4 d-flex justify-content-between">
                <h1 class="fs-4 px-2 d-flex align-items-center w-100">
                    <span class="shrink-0 flex items-center px-2">
                        <a href="{{ route('user_dashboard') }}">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                        </a>
                    </span>
                    <span class=" mx-3 text-light">
                        Quizzo
                    </span>
                </h1>

            </div>

            <ul class="list-unstyled px-2">

                <li  class="{{Route::is('admin.dashboard') ? 'active' : '' }}" >
                    <a href="{{ route('admin.dashboard') }}" class="text-decoration-none px-3 py-2 d-block"><i class="fa-solid fa-house"></i>
                        Dashboard</a>
                </li>

                {{-- topics --}}
                <li class="text-decoration-none px-3 py-2 d-block text-primary fs-6 fw-bold">Topics Management</li>
                <li class="{{Route::is('topics.create') ? 'active' : '' }}"><a href="{{route('topics.create')}}"
                        class="text-decoration-none px-3 py-2 d-block d-flex justify-content-between">
                        <span><i class="fa-solid fa-plus-circle"></i> Add Topic</span>
                    </a>
                </li>
                <li class="{{Route::is('topics.index') ? 'active' : '' }}"><a href="{{route('topics.index')}}"
                        class="text-decoration-none px-3 py-2 d-block d-flex justify-content-between">
                        <span><i class="fa-solid fa-book"></i> Topics</span>
                    </a>
                </li>

            {{--            Quizzes    --}}
                <li class="text-decoration-none px-3 py-2 d-block text-primary fs-6 fw-bold">Quizzes Management</li>
                <li class="{{Route::is('admin.CreateQuiz') ? 'active' : '' }}">
                    <a href="{{ route('admin.CreateQuiz') }}"
                       class="text-decoration-none px-3 py-2 d-block d-flex justify-content-between">
                        <span><i class="fa-solid fa-plus-circle"></i> Create Quiz</span>
                    </a>
                </li>

                <li class="{{Route::is('quiz.index') ? 'active' : '' }}">
                    <a href="{{ route('quiz.index') }}"
                       class="text-decoration-none px-3 py-2 d-block d-flex justify-content-between">
                        <span><i class="fa-solid fa-clipboard-question"></i> All Quizzes</span>
                    </a>
                </li>

{{--                     results    --}}
                <li class="text-decoration-none px-3 py-2 d-block text-primary fs-6 fw-bold">Results</li>
                <li class="{{Route::is('quiz.showresults') ? 'active' : '' }}">
                    <a href="{{ route('quiz.showresults') }}"
                       class="text-decoration-none px-3 py-2 d-block d-flex justify-content-between">
                        <span><i class="fa-solid fa-chart-bar"></i> Performance Summary</span>
                    </a>

                </li>



                {{-- admins --}}
                <li class="text-decoration-none px-3 py-2 d-block text-primary fs-6 fw-bold">Users Management</li>

                @if (Auth::user()->role === 'super_admin')
                    <li class="{{Route::is('admins.create') ? 'active' : '' }}">
                        <a href="{{ route('admins.create') }}"
                           class="text-decoration-none px-3 py-2 d-block d-flex justify-content-between">
                            <span><i class="fa-solid fa-plus-circle"></i> Add User</span>
                        </a>
                    </li>
                @endif

                <li class="{{Route::is('admins.showall') ? 'active' : '' }}">
                    <a href="{{ route('admins.showall') }}"
                       class="text-decoration-none px-3 py-2 d-block d-flex justify-content-between">
                        <span><i class="fa-solid fa-user-shield"></i> Admins</span>
                    </a>
                </li>

                <li class="{{Route::is('users.showall') ? 'active' : '' }}">
                    <a href="{{ route('users.showall') }}"
                       class="text-decoration-none px-3 py-2 d-block d-flex justify-content-between">
                        <span><i class="fa-solid fa-user"></i> Quiz Takers</span>
                    </a>
                </li>
                <li class="{{Route::is('allusers.showall') ? 'active' : '' }}">
                    <a href="{{ route('allusers.showall') }}"
                       class="text-decoration-none px-3 py-2 d-block d-flex justify-content-between">
                        <span><i class="fa-solid fa-users"></i> All Users</span>
                    </a>
                </li>
            </ul>

        </div>
        <div class="content">
            <nav class="navbar navbar-expand-md navbar-light bg-light">
                <div class="container-fluid d-flex justify-content-md-end ">
                    <div class="d-flex justify-content-between d-md-none d-block">
                        <button class="btn  open-btn me-2 "><i class="fa-solid fa-bars"></i></button>

                    </div>

                    <div class="p-2 ">

                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">

                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                        <div class="rounded-circle overflow-hidden d-flex justify-content-center align-items-center bg-light" style="width: 2em; height:2em;">
                                            @if(isset( auth()->user()->image))
                                            <img src="{{ asset('upload_images/' . auth()->user()->image) }}" alt="" style="object-fit: cover; width: 100%; height: 100%;">
                                            @else
                                            <i class="fa-solid fa-user"></i>
                                            @endif
                                        </div>
                                        <div class="ms-2">{{ Auth::user()->name }}</div>

                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Profile') }}
                                    </x-dropdown-link>

                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf

                                        <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault();
                                                            this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>

                    </div>
                </div>
            </nav>

            <div class="dashboard-content  px-3  m-md-5">
                <h2 class=" fs-6 text-primary mt-3"> @yield('page_title')</h2>
                {{$slot}}
            </div>
        </div>
    </div>

</body>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/all.min.js') }}"></script>
<script src="{{ asset('assets/js/jsfile.js') }}"></script>
<script>
    let go_up=document.getElementById("go-up");
    let go_down=document.getElementById("go-down");
    go_up.addEventListener("click",()=>{
        window.scrollTo({
            top: 0,
        });
    });
    go_down.addEventListener("click",()=>{
        window.scrollTo({
            top: document.documentElement.scrollHeight,
        });
    });
</script>
@yield("js_files")
</html>
