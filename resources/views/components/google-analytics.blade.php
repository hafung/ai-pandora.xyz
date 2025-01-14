@if(config('services.google.tracking_id'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google.tracking_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ config('services.google.tracking_id') }}');
    </script>
@endif
