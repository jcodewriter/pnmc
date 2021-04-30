<nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ $active == 'home' ? 'active' : '' }}" href="{{ route('admin') }}">
                    <span data-feather="home"></span>
                    {{ __('generic.dashboard') }}
                    @if($active == 'home')
                        <span class="sr-only">({{ __('generic.current') }})</span>
                    @endif
                </a>
            </li>
            @if(Auth::user()->admin->hasPermission('users'))
                <li class="nav-item">
                    <a class="nav-link {{ $active == 'users' ? 'active' : '' }}" href="{{ route('admin-users') }}">
                        <span data-feather="users"></span>
                        {{ __('generic.users') }}
                        @if($active == 'users')
                            <span class="sr-only">({{ __('generic.current') }})</span>
                        @endif
                    </a>
                </li>
            @endif

            @if(Auth::user()->admin->hasPermission('assets'))
                <li class="nav-item">
                    <a class="nav-link {{ $active == 'assets' ? 'active' : '' }}" href="{{ route('admin-assets') }}">
                        <span data-feather="database"></span>
                        {{ __('generic.assets') }}
                        @if($active == 'assets')
                            <span class="sr-only">({{ __('generic.current') }})</span>
                        @endif
                    </a>
                </li>
            @endif

            @if(Auth::user()->admin->hasPermission('translations'))
                <li class="nav-item">
                    <a class="nav-link {{ $active == 'translations' ? 'active' : '' }}" href="{{ route('admin-translations') }}">
                        <span data-feather="type"></span>
                        {{ __('generic.translations') }}
                        @if($active == 'translations')
                            <span class="sr-only">({{ __('generic.current') }})</span>
                        @endif
                    </a>
                </li>
            @endif
        </ul>

{{--        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">--}}
{{--            <span>Saved reports</span>--}}
{{--            <a class="d-flex align-items-center text-muted" href="#">--}}
{{--                <span data-feather="plus-circle"></span>--}}
{{--            </a>--}}
{{--        </h6>--}}
{{--        <ul class="nav flex-column mb-2">--}}
{{--            <li class="nav-item">--}}
{{--                <a class="nav-link" href="#">--}}
{{--                    <span data-feather="file-text"></span>--}}
{{--                    Current month--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            <li class="nav-item">--}}
{{--                <a class="nav-link" href="#">--}}
{{--                    <span data-feather="file-text"></span>--}}
{{--                    Last quarter--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            <li class="nav-item">--}}
{{--                <a class="nav-link" href="#">--}}
{{--                    <span data-feather="file-text"></span>--}}
{{--                    Social engagement--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            <li class="nav-item">--}}
{{--                <a class="nav-link" href="#">--}}
{{--                    <span data-feather="file-text"></span>--}}
{{--                    Year-end sale--}}
{{--                </a>--}}
{{--            </li>--}}
{{--        </ul>--}}
    </div>
</nav>
