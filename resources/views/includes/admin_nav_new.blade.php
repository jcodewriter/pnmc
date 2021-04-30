<ul class="nav">
    <li class="{{ $active == 'home' ? 'active' : '' }}">
        <a href="{{ route('admin') }}">
            <i class="now-ui-icons design_app"></i>
            <p>
                {{ __('generic.dashboard') }}
                @if($active == 'home')
                    <span class="sr-only">({{ __('generic.current') }})</span>
                @endif
            </p>
        </a>
    </li>

    @if(Auth::user()->admin->hasPermission('users'))
        <li class="{{ $active == 'users' ? 'active' : '' }}">
            <a href="{{ route('admin-users') }}">
                <i class="now-ui-icons users_single-02"></i>
                <p>
                    {{ __('generic.users') }}
                    @if($active == 'users')
                        <span class="sr-only">({{ __('generic.current') }})</span>
                    @endif
                </p>
            </a>
        </li>
    @endif

    @if(Auth::user()->admin->hasPermission('assets'))
        <li class="{{ $active == 'assets' ? 'active' : '' }}">
            <a href="{{ route('admin-assets') }}">
                <i class="now-ui-icons design_bullet-list-67"></i>
                <p>
                    {{ __('generic.assets') }}
                    @if($active == 'assets')
                        <span class="sr-only">({{ __('generic.current') }})</span>
                    @endif
                </p>
            </a>
        </li>
    @endif

    @if(Auth::user()->admin->hasPermission('translations'))
        <li class="{{ $active == 'translations' ? 'active' : '' }}">
            <a href="{{ route('admin-translations') }}">
                <i class="now-ui-icons text_caps-small"></i>
                <p>
                    {{ __('generic.translations') }}
                    @if($active == 'translations')
                        <span class="sr-only">({{ __('generic.current') }})</span>
                    @endif
                </p>
            </a>
        </li>
    @endif
</ul>
