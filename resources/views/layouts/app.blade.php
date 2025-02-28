<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ __('messages.coupon_system') }}</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link href="https://cdn.lineicons.com/5.0/lineicons.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>

<body>
    <nav class="navbar">
        <div class="container-fluid">
            <div class="row w-100">
                <div class="d-flex justify-content-between align-items-center">
                    <a class="navbar-brand" href="#"><img src="{{ asset('img/Logo.png') }}" class="w-75"
                            alt="Logo"></a>
                    <div class="language-switcher" onclick="switchLanguage()">
                        @if (app()->getLocale() == 'ar')
                            EN
                        @else
                            ع
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </nav>

    <div class="container my-4">
        @yield('content')
    </div>

    <script>
        function switchLanguage() {
            let currentLang = "{{ app()->getLocale() }}";
            let newLang = currentLang === "ar" ? "en" : "ar";
            window.location.href = "{{ route('language.switch', '') }}/" + newLang;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Include Footer -->
    @include('footer')

</body>

</html>
