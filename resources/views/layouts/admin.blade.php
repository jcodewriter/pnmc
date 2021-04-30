<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="google" content="notranslate">
    <meta http-equiv="Content-Language" content="{{ str_replace('_', '-', app()->getLocale()) }}">

    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">

    <title>
        {{ config('app.name') }}
        @hasSection('meta_title')
            - @yield('meta_title')
        @else
            @hasSection('title')
                - @yield('title')
            @endif
        @endif
    </title>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
    @stack('scripts')

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    @stack('css')
</head>
<body class="@theme">
    <div class="wrapper">
        <div class="sidebar" data-color="orange">
{{--        Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red | yellow"--}}
            <div class="sidebar-wrapper">
                @include('includes.admin_nav_new')
            </div>
        </div>
        <div class="main-panel">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-transparent  navbar-absolute bg-primary fixed-top">
                <div class="container-fluid">
                    <div class="navbar-wrapper">
                        <div class="navbar-toggle">
                            <button type="button" class="navbar-toggler">
                                <span class="navbar-toggler-bar bar1"></span>
                                <span class="navbar-toggler-bar bar2"></span>
                                <span class="navbar-toggler-bar bar3"></span>
                            </button>
                        </div>
                        <a class="navbar-brand" href="{{ route('admin') }}">
                            <img src="{{ asset('images/logo-white.png') }}" width="250" height="45" class="d-inline-block align-middle admin" alt="{{ config('app.name') }}">
                            @env('local')
                                <div class="d-inline-block">[LOCAL]</div>
                            @elseenv('staging')
                                <div class="d-inline-block">[STAGING]</div>
                            @endenv
                        </a>
                    </div>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-bar navbar-kebab"></span>
                        <span class="navbar-toggler-bar navbar-kebab"></span>
                        <span class="navbar-toggler-bar navbar-kebab"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end" id="navigation">
                        {{--
                        <form>
                            <div class="input-group no-border">
                                <input type="text" value="" class="form-control" placeholder="{{ __('generic.search') }}...">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="now-ui-icons ui-1_zoom-bold"></i>
                                    </div>
                                </div>
                            </div>
                        </form>
                        --}}
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}">
                                    <i class="now-ui-icons location_world"></i>
                                    <p>
                                        <span class="d-lg-none d-md-block">{{ __('generic.home') }}</span>
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link toggle-dark-mode" href="javascript:void(0)">
                                    <i class="fas fa-moon" style="font-size: medium; vertical-align: middle"></i>
                                    <p>
                                        <span class="d-lg-none d-md-block">{{ __('generic.toggle_dark_mode') }}</span>
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="now-ui-icons users_single-02"></i>
                                    <p>
                                        <span class="d-lg-none d-md-block">{{ __('auth.account') }}</span>
                                    </p>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('auth.logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- End Navbar -->
            <div class="panel-header @hasSection('graph') panel-header-lg @hasSection('breadcrumbs') panel-header-m @else panel-header-sm @endif @elseif(View::hasSection('description') || View::hasSection('subtitle')) panel-header-l @endif">
                @hasSection('graph')
                    @yield('graph')
                @elseif(View::hasSection('title'))
                    <nav aria-label="breadcrumb" role="navigation">
                        @yield('breadcrumbs')
                    </nav>

                    @hasSection('title')
                        <div class="header text-center">
                            <h2 class="title">
                                @yield('title')

                                @hasSection('subtitle')
                                    <small class="d-block">@yield('subtitle')</small>
                                @endif
                            </h2>

                            @hasSection('description')
                                <p class="category">@yield('description')</p>
                            @endif
                        </div>
                    @endif
                @endif
            </div>
            <div class="content">
                @yield('content')
            </div>
            <footer class="footer">
                <div class="container-fluid">
                    <div class="copyright">
                        &copy; 2019 <a href="https://factomize.com" target="_blank">Factomize</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</body>
</html>
