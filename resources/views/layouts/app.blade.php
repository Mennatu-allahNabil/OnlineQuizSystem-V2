<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/svg" href="{{ asset('favicon.svg') }}">


        <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])


        <!-- Bootstrap CSS -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">

        <!-- Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <!-- fontawesome -->
        <script src="https://kit.fontawesome.com/a74f5560d6.js" crossorigin="anonymous"></script>

        <!-- sweetalert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- trends -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


        @livewireStyles
<style>
    .custom-pattern {
        /*background:*/
        /*    conic-gradient(at 50% calc(100%/6),#1A8FE5 60deg,#0000 0),*/
        /*    conic-gradient(at calc(100%/6) 50%,#0000 240deg,#1A8FE5 0),*/
        /*    conic-gradient(from 180deg at calc(100%/6) calc(500%/6),#1A8FE5 60deg,#0000 0),*/
        /*    conic-gradient(from 180deg at calc(500%/6),#0000 240deg,#1A8FE5 0) calc(4*.866*16px) 0,*/
        /*    repeating-linear-gradient(-150deg,#1AE5D6 0 calc(100%/6),#0000   0 50%),*/
        /*    repeating-linear-gradient(-30deg, #fff 0 calc(100%/6),#E4E4ED 0 50%);*/
        /*background-size: calc(6*.866*16px) calc(3*16px);*/
    }

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
    </head>
    <body class="font-sans antialiased custom-pattern">

    <div class="go-up-down">
        <button type="button" id="go-up"><i class="fas fa-chevron-up"></i></button>
        <button type="button" id="go-down"><i class="fas fa-chevron-down"></i></button>
    </div>
        @include('sweetalert::alert')
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @livewireScripts
    </body>
    <x-footer/>
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
</html>

