<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <title>{{ __('messages.coupon_system')}}</title>
    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">{{ __('messages.coupon_system')}}</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <!-- Add links for Admin Dashboard or Logout if needed -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">Admin</a>
                    </li>
                </ul>
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

    <div class="container my-4">
        <!-- Main content here -->
        @yield('content')
    </div>

    <!-- Bootstrap JS (optional if you need interactivity) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
