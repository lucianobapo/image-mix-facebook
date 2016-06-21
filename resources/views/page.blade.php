<html>
    <head>
        <meta property="fb:app_id" content="{{ $app_id }}" />

        {{--<meta name="twitter:card" content="summary_large_image" />--}}
        {{--<meta name="twitter:site" content="@DeliveryLan" />--}}
        {{--<meta name="twitter:creator" content="@DeliveryLan" />--}}
        {{--<meta name="twitter:image:alt" content="{{ trans('delivery.head.metaDescription') }}" />--}}

        {{--<meta property="og:url" content="{{ $site }}"/>--}}
        <meta property="og:type" content="website"/>
        <meta property="og:title" content="{{ $title }}"/>
        {{--<meta property="og:site_name" content="{{ trans('delivery.index.title') }}"/>--}}
        <meta property="og:image" content="{!! $url !!}"/>
        {{--<meta property="og:image:url" content="{{ config('delivery.siteImage') }}"/>--}}
        {{--<meta property="og:image:secure_url" content="{{ config('delivery.siteSecureImage') }}"/>--}}
        {{--<meta property="og:image:type" content="image/png"/>--}}
        {{--<meta property="og:description" content="{{ trans('delivery.head.metaDescription') }}"/>--}}
        {{--<meta property="og:updated_time" content="{{ Carbon\Carbon::now()->timestamp }}"/>--}}
        <title>{{ $title }}</title>
    </head>
    <body>
        <img src="{{ $url }}">
    </body>
</html>