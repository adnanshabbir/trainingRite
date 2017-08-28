@extends('layouts.master')

@section('page-title','Inbound Messages Logs')

@section('page_breadcrumb')
    <li class="active">Inbound Messages</li>
@endsection

@section('page-content')
    <div class="page-content">

        <div class="page-header">
            <h1>
                Inbound Messages
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    See all inbound message logs
                </small>
            </h1>
        </div>
        <!-- /.page-header -->

        <div class="col-xs-12">
            @include('layouts.flash_messages')

            <div class="table-header">
                Results for "List of inbound message logs"
            </div>
            <!-- div.table-responsive -->

            <!-- div.dataTables_borderWrap -->
            <div>
                <div class="dataTables_wrapper form-inline no-footer">

                    <table id="inbound_message_logs"
                           class="table table-striped table-bordered table-hover dataTable no-footer DTTT_selectable">
                        <thead>
                        <tr>
                            <th>Sr #</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Message</th>
                            <th>Created At</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($inboundMessageLogs as $messageLog)
                            <tr>
                                <td>{{++$counter}}</td>
                                <td>{{$messageLog->from}}</td>
                                <td>{{$messageLog->to}}</td>
                                <td>{{$messageLog->body}}</td>
                                <td>{{$messageLog->created_at}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <!-- /.page-content -->
@endsection

@section('page-plugins-scripts')
    <!-- page specific plugin scripts -->
    <script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/js/jquery.dataTables.bootstrap.min.js')}}"></script>

@endsection

@section('page-scripts')

    <script>
        /**
         * Data Table block
         */
        jQuery(function ($) {
            $('#inbound_message_logs').dataTable();
        });
    </script>
@endsection
