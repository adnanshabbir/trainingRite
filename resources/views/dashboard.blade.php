@extends('layouts.master')
@section('page-title','Dashboard')

@section('page-content')
    <div class="page-content">

        <div class="row">
            <div class="col-xs-12">
            @include('layouts.flash_messages')
                <!-- PAGE CONTENT BEGINS -->
                <div class="row">
                    <div class="space-6"></div>

                    <div class="col-sm-8 infobox-container">


                        <!--Total Inbound Messages-->
                        <div class="infobox infobox-green">
                            <div class="infobox-icon">
                                <i class="ace-icon fa fa-comments"></i>
                            </div>

                            <div class="infobox-data">
						<span class="infobox-data-number">

                            {{$totalInboundMessages}}
                        </span>

                                <div class="infobox-content">Received Messages</div>
                            </div>

                        </div>


                        <!--Total Outbound Messages-->
                        <div class="infobox infobox-blue">
                            <div class="infobox-icon">
                                <i class="ace-icon fa fa-comments"></i>
                            </div>

                            <div class="infobox-data">
						<span class="infobox-data-number">
                                {{$totalOutboundMessages}}

                        </span>

                                <div class="infobox-content">Sent Messages</div>
                            </div>

                        </div>


                        <!--Total Numbers-->
                        <div class="infobox infobox-blue2">

                            <div class="infobox-icon">
                                <i class="ace-icon fa fa-phone"></i>
                            </div>

                            <div class="infobox-data">
						<span class="infobox-data-number">
                            <a href="{{route('update_sms_urls')}}"> Syc</a>
                        </span>
                                <div class="infobox-content">Update Inbound URL's</div>
                            </div>

                        </div>


                    </div>

                    <!-- /.col -->
                </div>
                <!-- PAGE CONTENT ENDS -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->

@endsection