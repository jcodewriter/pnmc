<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url('') }}">

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

    <!-- Primary Meta Tags -->
    <meta name="title" content="@yield('meta_title', config('app.name'))">
    <meta name="description" content="@yield('meta_description', 'Market cap, volume, supply, and other data for PegNet, the stablecoin network for decentralized finance.')">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ Request::url() }}">
    <meta property="og:title" content="@yield('meta_title', config('app.name'))">
    <meta property="og:description" content="@yield('meta_description', 'Market cap, volume, supply, and other data for PegNet, the stablecoin network for decentralized finance.')">
    <meta property="og:image" content="{{ asset('images/logo_meta.jpg') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ Request::url() }}">
    <meta property="twitter:title" content="@yield('meta_title', config('app.name'))">
    <meta property="twitter:description" content="@yield('meta_description', 'Market cap, volume, supply, and other data for PegNet, the stablecoin network for decentralized finance.')">
    <meta property="twitter:image" content="{{ asset('images/logo_meta.jpg') }}">

    <title>
        @hasSection('meta_title')
            @yield('meta_title') -
        @else
            @hasSection('title')
                @yield('title') -
            @endif
        @endif
        {{ config('app.name') }}
    </title>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
@stack('scripts')

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    @stack('css')

    @include('includes.google_analytics')
</head>
<body class="@theme">
<div class="wrapper">
    <div class="main-panel main-panel-no-sidebar">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute bg-primary fixed-top">
            <div class="container-fluid">
                <div class="navbar-wrapper">
                    <a class="navbar-brand" href="{{ route('home') }}">
                        <img src="{{ asset('images/logo-white.png') }}" width="250" height="45" class="d-inline-block align-middle" alt="{{ config('app.name') }}">
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
                            <a class="nav-link" href="https://pwallet.co" target="_blank">
                                <i class="now-ui-icons shopping_credit-card"></i>
                                <p>
                                    {{ __('pegnet.wallet') }}
                                </p>
                            </a>
                        </li>       
                        <li class="nav-item">
                            <a class="nav-link" href="https://ptrader.co" target="_blank">
                                <i class="now-ui-icons business_chart-bar-32"></i>
                                <p>
                                    {{ __('pegnet.trading_tools') }}
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://explorer.pegnetmarketcap.com/" target="_blank">
                                <i class="now-ui-icons files_paper"></i>
                                <p>
                                    {{ __('pegnet.pegnet_explorer') }}
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://forms.gle/cTxZWXQV7sW7jEY58" target="_blank">
                                <i class="now-ui-icons business_badge"></i>
                                <p>
                                    {{ __('pegnet.otc_buys') }}
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://prosperpool.io" target="_blank">
                                <i class="now-ui-icons objects_spaceship"></i>
                                <p>
                                    {{ __('pegnet.mining_pool') }}
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://pegnetdevs.com" target="_blank">
                                <i class="now-ui-icons design_app"></i>
                                <p>
                                    {{ __('pegnet.pegnet_devs') }}
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://chrome.google.com/webstore/detail/kambani/oiceedellfbhhplkfkpkkocbdkifpili?hl=en" target="_blank">
                                <i class="now-ui-icons arrows-1_cloud-download-93"></i>
                                <p>
                                    {{ __('pegnet.browser_extension') }}
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://pegnet.org/chat" target="_blank">
                                <svg width="24" height="24" viewBox="0 0 1000 1000" xmlns="http://www.w3.org/2000/svg"><path d="M 386 203C 387 203 388 203 388 203C 388 203 395 212 395 212C 267 248 209 304 209 304C 209 304 224 296 250 284C 326 250 386 241 411 239C 415 238 419 238 423 238C 466 232 515 231 566 236C 633 244 705 264 779 304C 779 304 723 251 603 214C 603 214 612 203 612 203C 612 203 709 201 811 277C 811 277 913 462 913 689C 913 689 853 792 697 797C 697 797 671 767 650 740C 743 714 778 656 778 656C 749 675 721 688 697 697C 661 712 627 722 594 728C 526 740 464 737 411 727C 371 719 336 708 307 697C 291 690 273 682 255 673C 253 671 251 670 249 669C 248 668 247 668 246 667C 233 660 226 655 226 655C 226 655 260 711 350 738C 329 765 303 797 303 797C 146 792 87 689 87 689C 87 462 189 277 189 277C 284 206 375 203 386 203C 386 203 386 203 386 203M 368 467C 327 467 296 502 296 545C 296 588 328 624 368 624C 408 624 440 588 440 545C 441 502 408 467 368 467C 368 467 368 467 368 467M 626 467C 586 467 554 502 554 545C 554 588 586 624 626 624C 666 624 698 588 698 545C 698 502 666 467 626 467C 626 467 626 467 626 467" fill="currentColor" /></svg>
                                <p>
                                    <span class="d-lg-none d-md-block">{{ __('pegnet.community_discord') }}</span>
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

                        @auth
                            @if(Auth::user()->is_admin)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin') }}">
                                        <i class="now-ui-icons ui-2_settings-90"></i>
                                        <p>
                                            <span class="d-lg-none d-md-block">{{ __('generic.admin') }}</span>
                                        </p>
                                    </a>
                                </li>
                            @endif

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="{{ route('home') }}" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
        <!-- End Navbar -->
        <div class="panel-header @hasSection('graph') panel-header-lg @elseif(!View::hasSection('title')) @hasSection('breadcrumbs') panel-header-m @else panel-header-sm @endif @elseif(View::hasSection('description') || View::hasSection('subtitle')) panel-header-l @endif">
            <nav aria-label="breadcrumb" role="navigation">
                @yield('breadcrumbs')
            </nav>

            @hasSection('graph')
                @yield('graph')
            @elseif(View::hasSection('title'))
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
                <nav>
                    <ul>
                        <li>
                            <a href="https://pegnet.org/" target="_blank">PegNet</a>
                        </li>
                        <li>
                            <a href="https://discord.gg/9rJVyEn" target="_blank">Discord</a>
                        </li>
                        <li>
                            <a href="https://factoshi.io/pegnet">{{ __('pegnet.network_stats') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('rich-list') }}">{{ __('pegnet.rich_list') }}</a>
                        </li>
                    </ul>
                </nav>
                <div class="copyright">
                    &copy; 2020 PegNetMarketCap
                    &bullet;
                    <a href="https://factomize.com/peg-api-auditing/" target="_blank" style="word-break: break-all">
                        Public Key: 90a5ad85e62dbc535f98c424429a3ea6e285538231ab1324136403cbdc459ae1
                    </a>
                </div>
            </div>
        </footer>
    </div>
</div>
</body>
</html>
