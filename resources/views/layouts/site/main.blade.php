<!doctype html>
<html class="no-js" lang="fa" dir="rtl">

@include('layouts.site.blocks.head')
<body>
{{--<style>--}}
{{--    .swal2-container {--}}
{{--        z-index: 11111111111 !important;--}}
{{--    }--}}
{{--</style>--}}
{{--@include('layouts.site.blocks.massage-swal')--}}
<div >
{{--    @include('layouts.site.blocks.sidebar')--}}
    <div class="main px-lg-4 px-md-4">
        @include('layouts.site.blocks.header')
        @yield('content')
    </div>
</div>
@include('layouts.site.blocks.script')
</body>
</html>
