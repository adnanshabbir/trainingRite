@extends('layouts.master')

@section('page-title','Conversation')

@section('page-plugins-styles')

    <link rel="stylesheet" href="{{asset('assets/css/jquery-ui.custom.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/css/jquery.gritter.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/css/select2.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/css/datepicker.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap-editable.min.css')}}"/>

    <style type="text/css">
        .breadcrumb {
            width: 97%;
        }
    </style>

@endsection
@section('page_breadcrumb')
    <li class="active">Conversation</li>
    <li style="width: 80%;">
        <a href="{{route('delete_all_conversations')}}" class="btn btn-success pull-right" 
        style="padding:0px 12px; margin-top:-7px;">
            Delete all conversations?
        </a>
    </li>
@endsection

@section('page-content')
    <div class="page-content">

        <!-- <div class="page-header">
            <h1>
                Conversations
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Select Plivo number from left and chat to customer from right
                </small>

                <a href="{{route('delete_all_conversations')}}" class="btn btn-success pull-right">Delete all
                    conversations?</a>
            </h1>
        </div> --><!-- /.page-header -->

        <div class="row">
            <div class="col-xs-12">
                @include('layouts.flash_messages')

                {{--Numbers Block--}}
                <div class="col-xs-12 col-sm-3 center">

                    <!-- <div class="widget-header widget-header-small">
                        <h4 class="widget-title blue smaller">
                            <i class="ace-icon fa fa-phone"></i>
                            Plivo Numbers
                        </h4>
                    </div> -->

                    @foreach($fromNumbers as $number)
                        <div class="space-2"></div>
                        <!-- <div class="hr hr2 hr-double"></div>
                        <div class="space-6"></div> -->
                        <div class="width-80 label label-info label-xlg arrowed-in arrowed-in-right">
                            <div class="inline position-relative">

                                <a class="user-title-label" href="{{route('conversation_details',$number->id)}}">
                                    <span class="badge badge-success" id="{{$number->number}}"></span>
                                    &nbsp;@if($number->friendly_name === null)
                                        <span class="white">{{$number->number}}</span>
                                    @else
                                        <span class="white">{{$number->friendly_name}}</span>
                                    @endif

                                </a>
                                &nbsp;&nbsp;&nbsp;
                                <a href="{{route('edit_number',$number->id)}}">
                                    <i class="ace-icon fa fa-pencil bigger-130" style="color: white;"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach

                </div>

                {{--Conversatioin Block--}}
                <div class="col-xs-12 col-sm-9" id="accordion" role="tablist" aria-multiselectable="true">
                    @if(empty($messages))

                        <div class="center-block">
                            <p class="alert alert-info">There is no conversation found. Please select a number from Left
                                side <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                        </div>
                    @else
                        @foreach($messages as $key => $value)
                            @include('conversations.chat')
                        @endforeach
                    @endif
                </div>
            </div><!-- /.col -->

            @include('conversations.modal')

        </div><!-- /.row -->
    </div><!-- /.page-content -->
@endsection


<!-- inline scripts related to this page -->
@section('page-scripts')
    <script type="text/javascript">
        jQuery(function ($) {
            $('.profile-feed-1').ace_scroll({
                height: '250px',
                mouseWheelLock: true,
                alwaysVisible: true
            });

            // get unread messages count after every 5 seconds
            window.setInterval(function () {
                countUnreadMessages();
            }, 5000);

        });


        function fetchConversation(number) {

            $.ajax({
                type: 'POST',
                url: '{{route('ajax_conversation_details')}}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'number_id': number
                },
                success: function (data) {
                    if (data) {

                        alert(JSON.stringify(data));

                    }

                }
            });
        }

        /**
         * Open modal, show templates, insert template in respective text area
         * and then close the modal
         * @param textAreaId
         */
        function showTemplates(textAreaId) {

            $('#templates').modal('show');
            $('#templates').on('click', '.add_new_template', function (event) {

                var id = $(this).attr('data-id');
                var msgTemplates =  <?php echo json_encode($templates);?>;
                $(msgTemplates).each(function (index, element) {

                    if (element.id == id) {

                        var templateConstant = element.template_body;
                        $('#' + textAreaId).val(templateConstant);
                        $('#' + textAreaId).focus();

                    }
                });

            });
        }


        /**
         * Get the unread messages count and update new messages counter
         */
        function countUnreadMessages() {

            $.ajax({
                type: 'POST',
                url: '{{route('unread_messages_count')}}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data) {
                        $(data).each(function (key, value) {
                            $('#' + value.number).text(value.count);
                        });
                    }
                }
            });
        }

    </script>
@endsection