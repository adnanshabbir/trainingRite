@extends('layouts.master')

@section('page-title','Edit Template')

@section('page-plugins-styles')
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap-timepicker.min.css')}}"/>
@endsection

@section('page_breadcrumb')
    <li>
        <a href="{{route('templates')}}">Template</a>
    </li>

    <li class="active">Edit Template</li>
@endsection

@section('page-content')
    <div class="page-content">

        <div class="page-header">
            <h1>
                Edit Number: {{$template->template_name}}
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Update template info...
                </small>
            </h1>
        </div>
        <!-- /.page-header -->

        <div class="row">

            <div class="col-xs-12">

            <!-- PAGE CONTENT BEGINS -->
                <form action="{{route('update_template',$template->id)}}" class="form-horizontal" role="form" method="post">

                    {{csrf_field()}}


                    {{-- Template Name --}}
                    <div class="form-group{{ $errors->has('template_name') ? ' has-error' : '' }}">
                        <label class="col-sm-3 control-label no-padding-right" for="template_name"> Template Name </label>

                        <div class="col-sm-9">
                            <input type="text" class="col-xs-10 col-sm-9" id="template_name" name="template_name"
                                      placeholder="Template Name" value="{{$template->template_name}}">
                            @if( $errors->has('template_name'))
                                <div class="alert alert-danger col-xs-9">
                                    <button type="button" class="close" data-dismiss="alert">
                                        <i class="ace-icon fa fa-times"></i>
                                    </button>
                                    {{ $errors->first('template_name') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="space-4"></div>


                    {{-- Template Body --}}
                    <div class="form-group{{ $errors->has('template_body') ? ' has-error' : '' }}">
                        <label class="col-sm-3 control-label no-padding-right" for="template_body"> Template Body </label>

                        <div class="col-sm-9">
                            <textarea class="col-xs-10 col-sm-9" id="template_body" name="template_body"
                                      placeholder="Template Body Text" rows="5">{{$template->template_body}}</textarea>
                            @if( $errors->has('template_body'))
                                <div class="alert alert-danger col-xs-9">
                                    <button type="button" class="close" data-dismiss="alert">
                                        <i class="ace-icon fa fa-times"></i>
                                    </button>
                                    {{ $errors->first('template_body') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="space-4"></div>


                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                Update Template
                            </button>

                            <a class="btn" href="{{route('templates')}}"><i class="ace-icon fa fa-undo bigger-110"></i>Back</a>

                        </div>

                    </div>

                </form>

                <!-- PAGE CONTENT ENDS -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.page-content -->
@endsection
@section('page-plugins-scripts')
    <script src="{{asset('assets/js/bootstrap-timepicker.min.js')}}"></script>
@endsection

@section('page-scripts')
    <script>
        jQuery(function ($) {
            $('#timepicker1').timepicker({
                minuteStep: 1,
                showSeconds: true,
                showMeridian: false
            }).next().on(ace.click_event, function () {
                $(this).prev().focus();
            });
        });
    </script>
@endsection



