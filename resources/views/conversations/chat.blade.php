<div class="widget-box transparent" role="tab" id="heading_{{$key}}">
    <div class="widget-header widget-header-small">
        <h4 class="widget-title blue smaller">
            <i class="ace-icon fa fa-envelope o"></i>
            Recent Messages Of {{$key}}
        </h4>

        <div class="widget-toolbar action-buttons">
            <a class="collapsed" data-toggle="collapse" data-parent="#accordion"
               href=".collapse_{{$key}}" aria-expanded="false" aria-controls="collapse_{{$key}}">
              Expand/Collapse
            </a>
        </div>

        <div class="widget-toolbar action-buttons">
            <a href="{{route('delete_conversation', $key)}}" class="pink">
                <i class="ace-icon fa fa-trash-o"></i>
            </a>
        </div>

    </div>

    <div class="widget-body">
        <div class="widget-main padding-8">
            <div id="" class="profile-feed profile-feed-1">
                <?php $loopCounter = 0; ?>
                @foreach($value as $element => $item)

                    @if($loopCounter < 1)

                        <div class="profile-activity clearfix">
                            <div>
                                @if($item['direction'] == 'inbound')
                                    <i class="pull-left thumbicon fa fa-undo btn-info no-hover"></i>
                                @else
                                    <i class="pull-left thumbicon fa fa-share btn-info no-hover"></i>
                                @endif
                                    <a class="user" href="#">{{ $item['from']}}</a>

                                {{$item['body']}}
                                <div class="time">
                                    <i class="ace-icon fa fa-clock-o bigger-110"></i>

                                    {{ \Carbon\Carbon::parse($item['created_at'])->diffForHumans() }}

                                </div>
                            </div>

                            <div class="tools action-buttons">
                                <a href="{{route('delete_message',$item['id'])}}" class="red">
                                    <i class="ace-icon fa fa-times bigger-125"></i>
                                </a>
                            </div>
                        </div>

                    @else

                        <div id="collapse_{{$key}}" class="profile-activity clearfix panel-collapse collapse collapse_{{$key}}" 
         role="tabpanel" aria-labelledby="heading_{{$key}}">
                            <div>
                                @if($item['direction'] == 'inbound')
                                    <i class="pull-left thumbicon fa fa-undo btn-info no-hover"></i>
                                @else
                                    <i class="pull-left thumbicon fa fa-share btn-info no-hover"></i>
                                @endif
                                    <a class="user" href="#">{{ $item['from']}}</a>

                                {{$item['body']}}
                                <div class="time">
                                    <i class="ace-icon fa fa-clock-o bigger-110"></i>

                                    {{ \Carbon\Carbon::parse($item['created_at'])->diffForHumans() }}

                                </div>
                            </div>

                            <div class="tools action-buttons">
                                <a href="{{route('delete_message',$item['id'])}}" class="red">
                                    <i class="ace-icon fa fa-times bigger-125"></i>
                                </a>
                            </div>
                        </div>

                    @endif

                    <?php $loopCounter++; ?>

                @endforeach

            </div>
        </div>
    </div>
</div>

<div class="center">
    <form action="{{route('conversation_send_message')}}" class="form-horizontal" role="form" method="post">

    {{csrf_field()}}

        <input type="hidden" name="to_numbers" value="{{$item['customer_number']}}">

        @if($item['direction'] == 'inbound')
            <input type="hidden" name="from_numbers" value="{{$item['to']}}">
        @else
            <input type="hidden" name="from_numbers" value="{{$item['from']}}">
        @endif

        <div class="form-group">
            <div class="col-sm-8">
            <textarea id="message_{{$item['id']}}" placeholder="Type your message here"
                      class="col-sm-12" name="message"></textarea>
            </div>

            <div class="col-sm-4">
                <button class="btn btn-info" type="submit">
                    Send Message
                </button>
                <button class="btn btn-group" onclick="showTemplates('message_{{$item['id']}}');" type="button">
                    Clipboard
                </button>
            </div>


        </div>
        <div class="space-4"></div>


    </form>
</div>

<div class="hr hr2 hr-double"></div>

<div class="space-6"></div>

