<form action="{{route('create_outbound_call')}}" enctype="multipart/form-data" class="form-horizontal" method="post">

    {{csrf_field()}}

    <div class="page-header">
        <h1>
            Make Outbound Calls
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Upload contacts csv and select from number to initiate outbound call
            </small>
        </h1>
    </div>

    {{--To Numbers--}}
    <div class="form-group{{ $errors->has('to_numbers') ? ' has-error' : '' }}">
        <label class="col-sm-4 control-label no-padding-right" for="template_name"> Upload Contacts
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


    {{--From number--}}
    <div class="form-group{{ $errors->has('from_number') ? ' has-error' : '' }}">
        <label class="col-sm-4 control-label no-padding-right" for="form-field-select-4"> Select a Twilio Number to Make
            Call</label>

        <div class="col-xs-5">
            <img class="form-control" id="ajax_process" src="{{asset('assets/ajax-loader-1.gif')}}"
                 style="display: none">
            <select class="chosen-select form-control numbers" name="from_number"
                    id="form-field-select-4"
                    data-placeholder="Choose Twilio numbers..."
                    style="display: none;">
            </select>
            @if( $errors->has('from_number'))
                <div class="alert alert-danger col-xs-9">
                    <button type="button" class="close" data-dismiss="alert">
                        <i class="ace-icon fa fa-times"></i>
                    </button>
                    {{ $errors->first('from_number') }}
                </div>
            @endif
        </div>
    </div>
    <div class="space-4"></div>


    <div class="clearfix form-actions">
        <div class="col-md-offset-3 col-md-9">

            <button class="btn btn-success" type="submit">
                <i class="ace-icon fa fa-phone bigger-110"></i>
                Make Calls
            </button>
        </div>
    </div>

</form>