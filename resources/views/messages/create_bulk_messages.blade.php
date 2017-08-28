@extends('layouts.master')

@section('page-title','Create Bulk Messages')

@section('page-plugins-styles')

    <!-- page specific plugin styles -->
    <link rel="stylesheet" href="{{asset('assets/css/jquery-ui.custom.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/css/chosen.min.css')}}"/>

    <link rel="stylesheet" href="{{asset('assets/css/datepicker.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap-timepicker.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/css/daterangepicker.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap-datetimepicker.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/css/colorpicker.min.cssd')}}"/>



@endsection

@section('page_breadcrumb')

    <li class="active">Bulk Messages</li>
@endsection

@section('page-content')
    <div class="page-content">

        <div class="page-header">
            <h1>
                Bulk Messages
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Upload CSV, chose Twilio numbers and send
                </small>
            </h1>
        </div>
        <!-- /.page-header -->

        <div class="row">

            <div class="col-xs-12">
            @include('layouts.flash_messages')

            <!-- PAGE CONTENT BEGINS -->
                <form action="{{route('send_bulk_messages')}}" class="form-horizontal" role="form" method="post"
                      enctype="multipart/form-data">

                    {{csrf_field()}}

                    {{--From number--}}
                    <div class="form-group{{ $errors->has('from_numbers') ? ' has-error' : '' }}">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-select-4"> From
                            Numbers</label>

                        <div class="col-xs-5">
                            <img class="form-control" id="ajax_process" src="{{asset('assets/ajax-loader-1.gif')}}"
                                 style="display: none">
                            <select multiple="" class="chosen-select form-control numbers" name="from_numbers[]"
                                    id="form-field-select-4"
                                    data-placeholder="Choose Twilio numbers..."
                                    style="display: none;">
                            </select>
                            @if( $errors->has('from_numbers'))
                                <div class="alert alert-danger col-xs-9">
                                    <button type="button" class="close" data-dismiss="alert">
                                        <i class="ace-icon fa fa-times"></i>
                                    </button>
                                    {{ $errors->first('from_numbers') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="space-4"></div>


                    {{--To Numbers--}}
                    <div class="form-group{{ $errors->has('to_numbers') ? ' has-error' : '' }}">
                        <label class="col-sm-3 control-label no-padding-right" for="template_name"> Upload Contacts
                            CSV</label>

                        <div class="col-sm-5">
                            <input type="file" id="id-input-file-2" name="to_numbers">

                            @if( $errors->has('to_numbers'))
                                <div class="alert alert-danger col-xs-9">
                                    <button type="button" class="close" data-dismiss="alert">
                                        <i class="ace-icon fa fa-times"></i>
                                    </button>
                                    {{ $errors->first('to_numbers') }}
                                </div>
                            @else
                                <label>
                                    <span class="lbl">Only CSV file is allowed</span>
                                </label>
                            @endif
                        </div>
                    </div>
                    <div class="space-4"></div>


                    <div class="form-group{{ $errors->has('is_schedule') ? ' has-error' : '' }}">
                        <label class="col-sm-3 control-label no-padding-right" for="scheduling"> Turn Scheduling</label>

                        <div class="col-sm-5">
                            <label class="col-sm-3 control-label no-padding-right">

                                <input name="is_schedule" id="scheduling" class="ace ace-switch ace-switch-4 btn-rotate" type="checkbox">
                                <span class="lbl"></span>
                            </label>
                            @if( $errors->has('is_schedule'))
                                <div class="alert alert-danger col-xs-9">
                                    <button type="button" class="close" data-dismiss="alert">
                                        <i class="ace-icon fa fa-times"></i>
                                    </button>
                                    {{ $errors->first('is_schedule') }}
                                </div>

                            @endif
                        </div>
                    </div>
                    <div class="space-4"></div>



                    {{--Set Scheduale--}}
                    <div class="form-group{{ $errors->has('schedule_at') ? ' has-error' : '' }}" style="display: none;" id="set_date_time">
                        <label class="col-sm-3 control-label no-padding-right" for="date-timepicker1"> Set Schedule</label>

                        <div class="col-sm-5">
                            <input id="date-timepicker1" type="text" class="form-control" name="schedule_at">
                            <span class="input-group-addon"><i class="fa fa-clock-o bigger-110"></i></span>
                            @if( $errors->has('schedule_at'))
                                <div class="alert alert-danger col-xs-9">
                                    <button type="button" class="close" data-dismiss="alert">
                                        <i class="ace-icon fa fa-times"></i>
                                    </button>
                                    {{ $errors->first('schedule_at') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="space-4"></div>



                    {{-- Template Body --}}
                    <div class="form-group{{ $errors->has('template_body') ? ' has-error' : '' }}">
                        <label class="col-sm-3 control-label no-padding-right" for="template_body"> Template
                            Body</label>

                        <div class="col-sm-7">
                            <textarea class="col-xs-10 col-sm-9" id="template_body" name="template_body"
                                      placeholder="Template Body Text" rows="8"></textarea>
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


                    {{--To number--}}
                    {{--@include('messages.chosen')--}}


                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                Send Messages
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

@section('page-plugins-scripts')

    <script src="{{asset('assets/js/chosen.jquery.min.js')}}"></script>

    <script src="{{asset('assets/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap-timepicker.min.js')}}"></script>
    <script src="{{asset('assets/js/moment.min.js')}}"></script>
    <script src="{{asset('assets/js/daterangepicker.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap-colorpicker.min.js')}}"></script>



@endsection

@section('page-scripts')

    <script type="text/javascript">
        jQuery(function ($) {

            // fetch twilio numbers
            fetchTwilioNumbers();

            $('#id-input-file-1 , #id-input-file-2').ace_file_input({
                no_file: 'No File ...',
                btn_choose: 'Choose',
                btn_change: 'Change',
                droppable: false,
                onchange: null,
                thumbnail: false, //| true | large
                whitelist: 'csv'
                //blacklist:'exe|php'
                //onchange:''
                //
            });


            $('#date-timepicker1').datetimepicker().next().on(ace.click_event, function () {
                $(this).prev().focus();
            });


            $(":checkbox").click(function(event) {
                if ($(this).is(":checked"))
                    $('#set_date_time').show();
                else
                    $('#set_date_time').hide();
            });

        });


        /**
         * Fetch Twilio numbers asynchronously
         */
        function fetchTwilioNumbers() {

            $(document).ajaxStart(function () {
                $("#ajax_process").show();
            });
            $(document).ajaxComplete(function () {
                $("#ajax_process").hide();
            });
            $.ajax({
                type: 'POST',
                dataType: "html",
                url: '{{route('get_from_numbers')}}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data) {
                        $('.numbers').append(data);

                        if (!ace.vars['touch']) {
                            $('.chosen-select').chosen({allow_single_deselect: true});
                            //resize the chosen on window resize
                            $(window)
                                .off('resize.chosen')
                                .on('resize.chosen', function () {
                                    $('.chosen-select').each(function () {
                                        var $this = $(this);
                                        $this.next().css({'width': $this.parent().width()});
                                    })
                                }).trigger('resize.chosen');
                        }
                    }
                }
            });


        }

    </script>
@endsection



