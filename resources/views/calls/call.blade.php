@extends('layouts.master')

@section('page-title','Call Settings')

@section('page_breadcrumb')
    <li class="active">Call Settings</li>
@endsection


@section('page-plugins-styles')

    <!-- page specific plugin styles -->
    <link rel="stylesheet" href="{{asset('assets/css/jquery-ui.custom.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/css/chosen.min.css')}}"/>
@endsection

@section('page-content')
    <div class="page-content">

        <div class="page-header">
            <h1>
                Call Settings
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Save Outbound Call flow
                </small>
            </h1>
        </div>
        <!-- /.page-header -->

        <div class="row">

            <div class="col-xs-12">
            @include('layouts.flash_messages')
            <!-- PAGE CONTENT BEGINS -->


                <form action="{{route('update_call_flow')}}" enctype="multipart/form-data" class="form-horizontal" method="post">

                {{csrf_field()}}


                    {{--Agent 1--}}
                    <div class="form-group{{ $errors->has('call_forward_1') ? ' has-error' : '' }}">
                        <label class="col-sm-4 control-label no-padding-right" for="call_forward_1"> Press 1 to Forward Call to Agent's # </label>
                        <div class="col-xs-5">
                            <input type="text" id="call_forward_1" class="col-xs-6 col-sm-6 input-mask-phone"
                                   name="call_forward_1" value="{{$callFlow->call_forward_1 or old('call_forward_1')}}">
                            @if( $errors->has('call_forward_1'))
                                <div class="alert alert-danger col-xs-9">
                                    <button type="button" class="close" data-dismiss="alert">
                                        <i class="ace-icon fa fa-times"></i>
                                    </button>
                                    {{ $errors->first('call_forward_1') }}
                                </div>
                            @endif
                        </div>

                    </div>
                    <div class="space-4"></div>


                    {{--Agent 2--}}
                    <div class="form-group{{ $errors->has('call_forward_2') ? ' has-error' : '' }}">
                        <label class="col-sm-4 control-label no-padding-right" for="call_forward_2"> Press 2 to Forward Call to Agent's #  </label>
                        <div class="col-xs-5">
                            <input type="text" id="call_forward_2" class="col-xs-6 col-sm-6 input-mask-phone"
                                   name="call_forward_2" value="{{$callFlow->call_forward_2 or old('call_forward_2')}}">
                            @if( $errors->has('call_forward_2'))
                                <div class="alert alert-danger col-xs-9">
                                    <button type="button" class="close" data-dismiss="alert">
                                        <i class="ace-icon fa fa-times"></i>
                                    </button>
                                    {{ $errors->first('call_forward_2') }}
                                </div>
                            @endif
                        </div>

                    </div>
                    <div class="space-4"></div>


                    {{--Agent 3--}}
                    <div class="form-group{{ $errors->has('call_forward_3') ? ' has-error' : '' }}">
                        <label class="col-sm-4 control-label no-padding-right" for="call_forward_3"> Press 3 to Forward Call to Agent's #  </label>
                        <div class="col-xs-5">
                            <input type="text" id="call_forward_3" class="col-xs-6 col-sm-6 input-mask-phone"
                                   name="call_forward_3" value="{{$callFlow->call_forward_3 or old('call_forward_3')}}">
                            @if( $errors->has('call_forward_3'))
                                <div class="alert alert-danger col-xs-9">
                                    <button type="button" class="close" data-dismiss="alert">
                                        <i class="ace-icon fa fa-times"></i>
                                    </button>
                                    {{ $errors->first('call_forward_3') }}
                                </div>
                            @endif
                        </div>

                    </div>
                    <div class="space-4"></div>


                    {{--Upload Greeting MP3--}}
                    <div class="form-group{{ $errors->has('greeting_mp3') ? ' has-error' : '' }}">
                        <label class="col-sm-4 control-label no-padding-right" for="template_name"> Upload Greeting MP3</label>

                        <div class="col-sm-5">
                            <input type="file" id="id-input-file-2" name="greeting_mp3">

                            @if( $errors->has('greeting_mp3'))
                                <div class="alert alert-danger col-xs-9">
                                    <button type="button" class="close" data-dismiss="alert">
                                        <i class="ace-icon fa fa-times"></i>
                                    </button>
                                    {{ $errors->first('greeting_mp3') }}
                                </div>
                                @elseif(null !== $callFlow->greeting_mp3)
                                <audio controls>
                                    <source src="{{asset('storage/greetings/'.$callFlow->greeting_mp3)}}" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            @else
                                <label>
                                    <span class="lbl">Only MP3 file is allowed</span>
                                </label>
                            @endif
                        </div>
                    </div>
                    <div class="space-4"></div>


                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                Update Call Settings
                            </button>

                        </div>
                    </div>

                </form>

                @include('calls.create_outbound_call')


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

    <script src="{{asset('assets/js/moment.min.js')}}"></script>
    <script src="{{asset('assets/js/jquery.maskedinput.min.js')}}"></script>


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
//                whitelist: 'csv'
                //blacklist:'exe|php'
                //onchange:''
                //
            });

            $.mask.definitions['~']='[+-]';
            $('.input-mask-phone').mask('(999) 999-9999');



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
