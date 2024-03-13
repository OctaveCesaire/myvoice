<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

        <!-- Scripts -->

        <script src="{{asset('')}}"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
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
        {{-- <script>
            function checkDate(event) {
                event.preventDefault(); // Empêche le comportement par défaut du formulaire

                var today = new Date();
                var inputError = document.getElementById('error');
                var launch = new Date(document.getElementById('launching_date').value);
                var ending = new Date(document.getElementById('ending_date').value);

                if (launch <= today && launch > ending) {
                    inputError.innerText = "La date incorrecte";
                    return false;
                }

                // Si les dates sont valides, le formulaire peut être envoyé
                return true;
            }
        </script> --}}
    </body>
</html>
