<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        @include('includes.header')
        @yield('style')
    </head>

    <body>

        @yield('content')

        @yield('script')
        
    </body>
</html>
