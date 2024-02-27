<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.0.0-beta19
* @link https://tabler.io
* Copyright 2018-2023 The Tabler Authors
* Copyright 2018-2023 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>@yield('pageTitle')</title>
    <!-- CSS files -->
    <link rel="shortcut icon" href="{{ \App\Models\Setting::find(1)->blog_favicon }}" type="image/x-icon">
    <link href="/back/dist/css/tabler.min.css?1684106062" rel="stylesheet" />
    <link href="/back/dist/css/tabler-flags.min.css?1684106062" rel="stylesheet" />
    <link href="/back/dist/css/tabler-payments.min.css?1684106062" rel="stylesheet" />
    <link href="/back/dist/css/tabler-vendors.min.css?1684106062" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <link rel="stylesheet" href="/back/dist/libs/ijaboCropTool/ijaboCropTool.min.css">

    <link rel="stylesheet" href="/jquery-ui-1.13.2/jquery-ui.min.css">
    <link rel="stylesheet" href="/jquery-ui-1.13.2/jquery-ui.structure.min.css">
    <link rel="stylesheet" href="/jquery-ui-1.13.2/jquery-ui.theme.min.css">

    <link rel="stylesheet" href="/amsify/amsify.suggestags.css">

    <script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/ckeditor.js"></script>

    @stack('stylesheets')
    @livewireStyles
    <link href="/back/dist/css/demo.min.css?1684106062" rel="stylesheet" />
    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>
</head>

<body>
    <script src="/back/dist/js/demo-theme.min.js?1684106062"></script>
    <div class="page">
        <!-- Navbar -->
        @include('back.layouts.inc.header')
        <div class="page-wrapper">
            <!-- Page header -->
            <div class="page-header d-print-none">
                <div class="container-xl">
                    @yield('pageHeader')
                </div>
            </div>
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">
                    @yield('content')
                </div>
            </div>
            @include('back.layouts.inc.footer')
        </div>
    </div>

    <!-- Libs JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}

    {{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="/amsify/jquery.amsify.suggestags.js"></script>

    <script src="/back/dist/libs/ijaboCropTool/ijaboCropTool.min.js"></script>

    <script src="/back/dist/libs/apexcharts/dist/apexcharts.min.js?1684106062" defer></script>
    <script src="/back/dist/libs/jsvectormap/dist/js/jsvectormap.min.js?1684106062" defer></script>
    <script src="/back/dist/libs/jsvectormap/dist/maps/world.js?1684106062" defer></script>
    <script src="/back/dist/libs/jsvectormap/dist/maps/world-merc.js?1684106062" defer></script>

    <script src="/jquery-ui-1.13.2/jquery-ui.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>


    <!-- Tabler Core -->
    <script src="/back/dist/js/tabler.min.js?1684106062" defer></script>

    @livewireScripts


    <script>
        jQuery.noConflict();
        jQuery('input[name="post_tags"]').amsifySuggestags();

        jQuery(document).ready(function() {
            toastr.options = {
                "progressBar": true,
                "positionClass": "toast-top-right"
            }
        });
        window.addEventListener('success', event => {
            toastr.success(event.detail.message);
        });
        window.addEventListener('error', event => {
            toastr.error(event.detail.message);
        });
        window.addEventListener('warning', event => {
            toastr.warning(event.detail.message);
        });
        // window.addEventListener('showToastr', function(event){
        //     toastr.remove();
        //     if(event.detail.type === 'info'){
        //         toastr.info(event.detail.message);
        //     }else if(event.detail.type === 'success'){
        //         toastr.success(event.detail.message);
        //     }
        // });
    </script>

    <script src="/back/dist/js/demo.min.js?1684106062" defer></script>

    @stack('scripts')
</body>

</html>
