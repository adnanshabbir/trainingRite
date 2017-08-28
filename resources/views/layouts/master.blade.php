<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta charset="utf-8"/>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page-title')</title>

    <meta name="description" content=""/>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>

    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/font-awesome/4.2.0/css/font-awesome.min.css')}}"/>

    <!-- page specific plugin styles -->
@yield('page-plugins-styles')

<!-- text fonts -->
    <link rel="stylesheet" href="{{asset('assets/fonts/fonts.googleapis.com.css')}}"/>

    <!-- ace styles -->
    <link rel="stylesheet" href="{{asset('assets/css/ace.min.css')}}" class="ace-main-stylesheet" id="main-ace-style"/>

    <!-- inline styles related to this page -->
@yield('page-styles')

<!-- ace settings handler -->
    <script src="{{asset('assets/js/ace-extra.min.js')}}"></script>

</head>

<body class="no-skin">
<div id="navbar" class="navbar navbar-default">
    <script type="text/javascript">
        try {
            ace.settings.check('navbar', 'fixed')
        } catch (e) {
        }
    </script>


    @include('layouts.top-navbar')
</div>

<div class="main-container" id="main-container">
    <script type="text/javascript">
        try {
            ace.settings.check('main-container', 'fixed')
        } catch (e) {
        }
    </script>

    <div id="sidebar" class="sidebar responsive">

        @include('layouts.sidebar')

    </div>

    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs" id="breadcrumbs">
                <script type="text/javascript">
                    try {
                        ace.settings.check('breadcrumbs', 'fixed')
                    } catch (e) {
                    }
                </script>

                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="#">Home</a>
                    </li>

                    @yield('page_breadcrumb')

                </ul><!-- /.breadcrumb -->

            </div>

            @yield('page-content')

        </div>
    </div><!-- /.main-content -->

    <div class="footer">
        @include('layouts.footer')
    </div>

    <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
    </a>
</div><!-- /.main-container -->

<!-- basic scripts -->

<script src="{{asset('assets/js/jquery.2.1.1.min.js')}}"></script>


<script src="{{asset('assets/js/bootstrap.min.js')}}"></script>

<!-- page specific plugin scripts -->
@yield('page-plugins-scripts')

<!-- ace scripts -->
<script src="{{asset('assets/js/ace-elements.min.js')}}"></script>
<script src="{{asset('assets/js/ace.min.js')}}"></script>

<!-- inline scripts related to this page -->
@yield('page-scripts')
</body>
</html>
