@extends('layouts.master')

@section('page-title','Settings')

@section('page_breadcrumb')
    <li class="active">Settings</li>
@endsection

@section('page-content')
    <div class="page-content">

        <div class="page-header">
            <h1>
                Settings
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Save API credentials and settings here
                </small>
            </h1>
        </div>
        <!-- /.page-header -->

        <div class="row">

            <div class="col-xs-12">
            @include('layouts.flash_messages')
            <!-- PAGE CONTENT BEGINS -->
                <form action="{{route('update_settings')}}" class="form-horizontal" role="form" method="post">

                {{csrf_field()}}

                {{--Plivo  Authentication Id--}}
                    <div class="form-group{{ $errors->has('account_sid') ? ' has-error' : '' }}">
                        <label class="col-sm-3 control-label no-padding-right" for="account_sid"> Authentication ID </label>
                        <div class="col-sm-9">
                            <input type="text" id="account_sid" class="col-xs-10 col-sm-9"
                                   name="account_sid" value="{{$settings->account_sid or old('account_sid')}}">
                            @if( $errors->has('account_sid'))
                                <div class="alert alert-danger col-xs-9">
                                    <button type="button" class="close" data-dismiss="alert">
                                        <i class="ace-icon fa fa-times"></i>
                                    </button>
                                    {{ $errors->first('account_sid') }}
                                </div>
                            @endif
                        </div>

                    </div>
                    <div class="space-4"></div>


                    {{--Plivo  Authentication Token--}}
                    <div class="form-group{{ $errors->has('auth_token') ? ' has-error' : '' }}">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Authentication Token </label>

                        <div class="col-sm-9">
                            <input type="text" id="form-field-1" name="auth_token" class="col-xs-10 col-sm-9"
                                   value="{{$settings->auth_token or old('auth_token')}}">
                            @if( $errors->has('auth_token'))
                                <div class="alert alert-danger col-xs-9">
                                    <button type="button" class="close" data-dismiss="alert">
                                        <i class="ace-icon fa fa-times"></i>
                                    </button>
                                    {{ $errors->first('auth_token') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="space-4"></div>

                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                Update Settings
                            </button>
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