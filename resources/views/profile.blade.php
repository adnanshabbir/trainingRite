@extends('layouts.master')

@section('page-title','Profile')

@section('page_breadcrumb')
    <li class="active">Profile</li>
@endsection

@section('page-content')
    <div class="page-content">

        <div class="page-header">
            <h1>
                Profile
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    User Profile
                </small>
            </h1>
        </div>
        <!-- /.page-header -->

        <div class="row">

            <div class="col-xs-12">
            @include('layouts.flash_messages')
            <!-- PAGE CONTENT BEGINS -->
                <form action="{{route('update_profile')}}" class="form-horizontal" role="form" method="post">

                {{csrf_field()}}

                <!--Full Name-->
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label class="col-sm-3 control-label no-padding-right" for="name"> Full Name </label>
                        <div class="col-sm-9">
                            <input type="text" id="name" placeholder="Jhon Deo" class="col-xs-10 col-sm-9"
                                   name="name" value="{{$profile->name or old('name')}}">
                            @if( $errors->has('name'))
                                <div class="alert alert-danger col-xs-9">
                                    <button type="button" class="close" data-dismiss="alert">
                                        <i class="ace-icon fa fa-times"></i>
                                    </button>
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                        </div>

                    </div>
                    <div class="space-4"></div>


                    <!--Email-->
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Email </label>

                        <div class="col-sm-9">
                            <input type="text" id="form-field-1" name="email" placeholder="email@example.com"
                                   class="col-xs-10 col-sm-9" value="{{$profile->email or old('email')}}">
                            @if( $errors->has('email'))
                                <div class="alert alert-danger col-xs-9">
                                    <button type="button" class="close" data-dismiss="alert">
                                        <i class="ace-icon fa fa-times"></i>
                                    </button>
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="space-4"></div>


                    {{--Password--}}
                    <div id="pwd-container3"
                         class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password3" class="col-sm-3 control-label no-padding-right">Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="col-xs-10 col-sm-9" id="password3" name="password"
                                   placeholder="Password">
                            @if( $errors->has('password'))
                                <div class="alert alert-danger col-xs-9">
                                    <button type="button" class="close" data-dismiss="alert">
                                        <i class="ace-icon fa fa-times"></i>
                                    </button>
                                    {{ $errors->first('password') }}
                                </div>
                            @endif

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="col-sm-3 control-label no-padding-right">Confirm
                            Password</label>
                        <div class="col-sm-9">
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                   class="col-xs-10 col-sm-9">
                        </div>
                    </div>


                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                Update Profile
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