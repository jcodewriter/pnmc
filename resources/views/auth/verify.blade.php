@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('auth.verify_your_email_address') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('auth.a_fresh_verification_link_sent') }}
                        </div>
                    @endif

                    {{ __('auth.check_email_before_proceeding') }}
                    {{ __('auth.if_you_did_not_receive') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
		                <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('auth.click_here_to_request_new') }}</button>.
	                </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
