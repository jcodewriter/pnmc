@if (config('services.google_analytics.property'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google_analytics.property') }}"></script>
    <script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('config', '{{ config('services.google_analytics.property') }}', {
        @if (config('services.google_analytics.domain'))
		    'cookie_domain': '{{ config('services.google_analytics.domain') }}',
		@endif
        @if (config('services.google_analytics.anonymise'))
			'anonymize_ip': true,
        @endif
		});
    </script>
@endif
