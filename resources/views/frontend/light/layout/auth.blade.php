<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="description" content="{{ $page->seo_description ?? Config::config()->seo_description }}" />
    <meta name="keywords" content="{{ implode(',', $page->seo_keywords ?? Config::config()->seo_tags) }} ">

    <title>{{ Config::config()->appname }}</title>

    <link rel="shortcut icon" type="image/png" href="{{ Config::getFile('icon', Config::config()->favicon, true) }}">

    <link rel="stylesheet" href="{{ Config::cssLib('frontend', 'lib/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ Config::cssLib('frontend', 'all.min.css') }}">
    <link rel="stylesheet" href="{{ Config::cssLib('frontend', 'line-awesome.min.css') }}">

    @if (Config::config()->alert === 'izi')
        <link rel="stylesheet" href="{{ Config::cssLib('frontend', 'izitoast.min.css') }}">
    @elseif(Config::config()->alert === 'toast')
        <link href="{{ Config::cssLib('frontend', 'toastr.min.css') }}" rel="stylesheet">
    @else
        <link href="{{ Config::cssLib('frontend', 'sweetalert.min.css') }}" rel="stylesheet">
    @endif


    @php
        $heading = optional(Config::config()->fonts)->heading_font_family;
        $paragraph = optional(Config::config()->fonts)->paragraph_font_family;
    @endphp

    <style>
        :root {
            --h-font: <?=$heading ?>;
            --p-font: <?=$paragraph ?>;
        }
    </style>

    <style>
        .user-pages-body.user-dark-mode {
            background: #0b1120;
            color: #d7e0ea;
        }

        .user-pages-body.user-dark-mode .dashbaord-main {
            background:
                radial-gradient(circle at top left, rgba(56, 189, 248, 0.12), transparent 28%),
                radial-gradient(circle at top right, rgba(14, 165, 233, 0.10), transparent 24%),
                linear-gradient(180deg, #08101d 0%, #0f172a 100%);
            color: #d7e0ea;
        }

        .user-pages-body.user-dark-mode .user-header,
        .user-pages-body.user-dark-mode .user-sidebar,
        .user-pages-body.user-dark-mode .d-card,
        .user-pages-body.user-dark-mode .sp_site_card,
        .user-pages-body.user-dark-mode .modal-content,
        .user-pages-body.user-dark-mode .dropdown-menu,
        .user-pages-body.user-dark-mode .mobile-bottom-menu-wrapper {
            background: rgba(15, 23, 42, 0.92);
            color: #d7e0ea;
            border-color: rgba(148, 163, 184, 0.18);
            box-shadow: 0 18px 45px rgba(2, 6, 23, 0.28);
        }

        .user-pages-body.user-dark-mode .user-header {
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(148, 163, 184, 0.12);
        }

        .user-pages-body.user-dark-mode .user-sidebar {
            background:
                linear-gradient(180deg, rgba(7, 14, 29, 0.98) 0%, rgba(15, 23, 42, 0.96) 100%);
            border-right: 1px solid rgba(148, 163, 184, 0.12);
        }

        .user-pages-body.user-dark-mode .d-left-wrapper .d-card,
        .user-pages-body.user-dark-mode .d-card.not-hover,
        .user-pages-body.user-dark-mode .sp_site_card .card-header,
        .user-pages-body.user-dark-mode .sp_site_card .card-body,
        .user-pages-body.user-dark-mode .sp_site_card .card-footer {
            background: rgba(15, 23, 42, 0.86);
            color: #d7e0ea;
            border-color: rgba(148, 163, 184, 0.16);
        }

        .user-pages-body.user-dark-mode h1,
        .user-pages-body.user-dark-mode h2,
        .user-pages-body.user-dark-mode h3,
        .user-pages-body.user-dark-mode h4,
        .user-pages-body.user-dark-mode h5,
        .user-pages-body.user-dark-mode h6,
        .user-pages-body.user-dark-mode strong,
        .user-pages-body.user-dark-mode label,
        .user-pages-body.user-dark-mode .user-btn,
        .user-pages-body.user-dark-mode .dropdown-item,
        .user-pages-body.user-dark-mode .single-recent-transaction .content .title,
        .user-pages-body.user-dark-mode .table td,
        .user-pages-body.user-dark-mode .table th,
        .user-pages-body.user-dark-mode .sp_site_table tbody tr td,
        .user-pages-body.user-dark-mode .sp_site_table thead th,
        .user-pages-body.user-dark-mode .d-card .d-card-amount,
        .user-pages-body.user-dark-mode .d-card-balance {
            color: #f8fafc !important;
        }

        .user-pages-body.user-dark-mode p,
        .user-pages-body.user-dark-mode span,
        .user-pages-body.user-dark-mode small,
        .user-pages-body.user-dark-mode li,
        .user-pages-body.user-dark-mode .text-muted,
        .user-pages-body.user-dark-mode .single-recent-transaction .content span,
        .user-pages-body.user-dark-mode .d-card .d-card-caption,
        .user-pages-body.user-dark-mode .table-date,
        .user-pages-body.user-dark-mode .dropdown-item i {
            color: #94a3b8 !important;
        }

        .user-pages-body.user-dark-mode .sidebar-menu > li > a,
        .user-pages-body.user-dark-mode .sidebar-menu .submenu li a,
        .user-pages-body.user-dark-mode .mobile-bottom-menu li a {
            color: #cbd5e1 !important;
            background: transparent;
            border-color: transparent;
        }

        .user-pages-body.user-dark-mode .sidebar-menu > li.active > a,
        .user-pages-body.user-dark-mode .sidebar-menu > li > a:hover,
        .user-pages-body.user-dark-mode .sidebar-menu .submenu li a:hover,
        .user-pages-body.user-dark-mode .mobile-bottom-menu li a.active,
        .user-pages-body.user-dark-mode .mobile-bottom-menu li a:hover {
            color: #f8fafc !important;
            background: rgba(56, 189, 248, 0.16);
        }

        .user-pages-body.user-dark-mode .sidebar-menu .submenu {
            background: rgba(2, 6, 23, 0.28);
        }

        .user-pages-body.user-dark-mode .form-control,
        .user-pages-body.user-dark-mode .form-select,
        .user-pages-body.user-dark-mode .input-group-text,
        .user-pages-body.user-dark-mode textarea,
        .user-pages-body.user-dark-mode select {
            background: #0f172a;
            color: #e2e8f0;
            border-color: rgba(148, 163, 184, 0.22);
        }

        .user-pages-body.user-dark-mode .form-control::placeholder,
        .user-pages-body.user-dark-mode textarea::placeholder {
            color: #64748b;
        }

        .user-pages-body.user-dark-mode .form-control:focus,
        .user-pages-body.user-dark-mode .form-select:focus,
        .user-pages-body.user-dark-mode textarea:focus {
            background: #0f172a;
            color: #f8fafc;
            border-color: rgba(56, 189, 248, 0.5);
            box-shadow: 0 0 0 0.2rem rgba(56, 189, 248, 0.12);
        }

        .user-pages-body.user-dark-mode .form-select option {
            background: #0f172a;
            color: #e2e8f0;
        }

        .user-pages-body.user-dark-mode .form-control[readonly],
        .user-pages-body.user-dark-mode .form-control:disabled,
        .user-pages-body.user-dark-mode .form-control[disabled] {
            background: #111827;
        }

        .user-pages-body.user-dark-mode .table,
        .user-pages-body.user-dark-mode .sp_site_table,
        .user-pages-body.user-dark-mode .sp_site_table tbody tr td,
        .user-pages-body.user-dark-mode .sp_site_table thead th {
            border-color: rgba(148, 163, 184, 0.14) !important;
            background: transparent;
        }

        .user-pages-body.user-dark-mode .dropdown-menu .dropdown-item:hover,
        .user-pages-body.user-dark-mode .dropdown-menu .dropdown-item:focus {
            background: rgba(56, 189, 248, 0.12);
            color: #f8fafc !important;
        }

        .user-pages-body.user-dark-mode hr,
        .user-pages-body.user-dark-mode .single-recent-transaction,
        .user-pages-body.user-dark-mode .recent-transaction-list .single-recent-transaction + .single-recent-transaction {
            border-color: rgba(148, 163, 184, 0.12);
        }

        .user-pages-body.user-dark-mode .countdown-single,
        .user-pages-body.user-dark-mode .user-sidebar-bottom,
        .user-pages-body.user-dark-mode .health-bar,
        .user-pages-body.user-dark-mode .progress {
            background: rgba(148, 163, 184, 0.10);
        }

        .user-pages-body.user-dark-mode .noti-count {
            background: #38bdf8;
            color: #08101d !important;
        }

        .user-pages-body.user-dark-mode .sidebar-toggeler,
        .user-pages-body.user-dark-mode .sidebar-open-btn a {
            color: #f8fafc;
        }

        .user-pages-body.user-dark-mode .pagination .page-link {
            background: #0f172a;
            color: #cbd5e1;
            border-color: rgba(148, 163, 184, 0.18);
        }

        .user-pages-body.user-dark-mode .pagination .page-item.active .page-link,
        .user-pages-body.user-dark-mode .pagination .page-link:hover {
            background: #0ea5e9;
            border-color: #0ea5e9;
            color: #08101d;
        }
    </style>

    @stack('external-css')

    <link rel="stylesheet" href="{{ Config::cssLib('frontend', 'main.css') }}">

    @stack('style')

</head>

<body class="user-pages-body user-dark-mode">

    @include(Config::theme() . 'layout.user_sidebar')

    <header class="user-header">
        <a href="{{ route('user.dashboard') }}" class="site-logo">
            <img src="{{ Config::getFile('dark_logo', Config::config()->dark_logo, true) }}" alt="image">
        </a>

        <button type="button" class="sidebar-toggeler"><i class="las la-bars"></i></button>



        <div class="dropdown user-dropdown">
            <a type="button" target="_blank" href="{{ route('home') }}"
                class="btn sp_theme_btn btn-sm">{{ __('Visit Home') }}</a>
            <button class="user-btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                aria-expanded="false">
                <img src="{{ Config::getFile('user', auth()->user()->image, true) }}" alt="image">
                <span>{{ auth()->user()->username }}</span>
                <small class="text-muted d-block">{{ auth()->user()->level ?? 'VIP1' }}</small>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                <li><a class="dropdown-item" href="{{ route('user.profile') }}"><i class="far fa-user-circle me-2"></i>
                        {{ __('Profile') }}</a></li>
                <li><a class="dropdown-item" href="{{ route('user.2fa') }}"><i class="fas fa-cog me-2"></i>
                        {{ __('2FA') }}</a></li>
                <li><a class="dropdown-item" href="{{ route('user.logout') }}"><i class="fas fa-sign-out-alt me-2"></i>
                        {{ __('Logout') }}</a></li>
            </ul>
        </div>
    </header>

    <main class="dashbaord-main">
        @yield('content')
    </main>

    <script src="{{ Config::jsLib('frontend', 'lib/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ Config::jsLib('frontend', 'lib/jquery.min.js') }}"></script>
    <script src="{{ Config::jsLib('frontend', 'lib/wow.min.js') }}"></script>
    <script src="{{ Config::jsLib('frontend', 'lib/jquery.paroller.min.js') }}"></script>
    <script src="{{ Config::jsLib('frontend', 'lib/slick.min.js') }}"></script>

    @stack('external-script')


    @if (Config::config()->alert === 'izi')
        <script src="{{ Config::jsLib('frontend', 'izitoast.min.js') }}"></script>
    @elseif(Config::config()->alert === 'toast')
        <script src="{{ Config::jsLib('frontend', 'toastr.min.js') }}"></script>
    @else
        <script src="{{ Config::jsLib('frontend', 'sweetalert.min.js') }}"></script>
    @endif

    <script src="{{ Config::jsLib('frontend', 'main.js') }}"></script>

    @include('alert')

    @stack('script')

    <script>
        'use strict'


        $(".sidebar-menu>li>a").each(function() {
            let submenuParent = $(this).parent('li');

            $(this).on('click', function() {
                submenuParent.toggleClass('open')
            })
        });

        $('.sidebar-open-btn').on('click', function() {
            $(this).toggleClass('active');
            $('.user-sidebar').toggleClass('active');
            $('.dashbaord-main').toggleClass('active');
        });
    </script>

</body>

</html>
