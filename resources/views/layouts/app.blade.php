<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>
    <meta name="description" content="A calendar of events happening in Wichita, Kansas.">

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png">
    <link rel="manifest" href="/favicons/manifest.json">
    <link rel="mask-icon" href="/favicons/safari-pinned-tab.svg" color="#ed3143">
    <link rel="shortcut icon" href="/favicons/favicon.ico">
    <meta name="msapplication-config" content="/favicons/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">

    <!-- Open Graph & Twitter share data -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="@eventsinwichita">
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ config('app.name') }}">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:description" content="A calendar of events happening in Wichita, Kansas.">
    <meta property="og:image" content="{{ asset('images/social.jpg') }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.App = {!! json_encode([
            'csrfToken' => csrf_token(),
            'user' => Auth::user(),
            'facebook_token' => env('FACEBOOK_CLIENT_TOKEN')
        ]) !!};
    </script>
</head>
<body>
    <div id="app">
        @yield('main')

        <error></error>

        <flash message="{{ session('flash') }}"></flash>
    </div>

    <!-- Scripts -->
    <script src="/js/app.js"></script>

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-66355445-1', 'auto');
        ga('send', 'pageview');
    </script>
</body>
</html>
