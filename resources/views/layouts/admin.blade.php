<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <title>{{ __('messages.admin_dashboard') }}</title>
    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">{{ __('messages.admin_panel') }}</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">{{ __('messages.dashboard') }}</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.successful.coupons') }}">
                            {{ __('messages.used_coupons') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                            href="{{ route('admin.subscribers.list') }}">{{ __('messages.subscribers') }}</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.stats') }}">
                            {{ __('messages.stats') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            {{ __('messages.logout') }}
                        </a>
                    </li>
                </ul>

                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="languageDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        {{ app()->getLocale() == 'ar' ? 'العربية' : 'English' }}
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="languageDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('language.switch', 'en') }}">
                                🇬🇧 English
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('language.switch', 'ar') }}">
                                🇸🇦 العربية
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container my-3">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
