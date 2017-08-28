
{{--Show Messages Templates--}}

<div class="modal inmodal fade" id="templates"  role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
                <h4 class="modal-title">Message Templates</h4>
                <small class="font-bold">All message templates available to insert in the campaign text body</small>
            </div>
            <div class="modal-body" id="modelBody">

                {{--message_templates--}}
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                        <tr>
                            <th>Serial #</th>
                            <th>Title</th>
                            <th>Template</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($templates as $template)
                            <tr class="gradeC">
                                <td>{{++$counter}}</td>
                                <td>{{$template->template_name}}</td>
                                <td>{{$template->template_body}}</td>

                                <td>
                                    <button type="button" data-dismiss="modal" data-id="{{$template->id}}"
                                            class="btn btn-success add_new_template">Insert
                                    </button>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                        <tfoot>
                        <tr>
                            <th>Serial #</th>
                            <th>Title</th>
                            <th>Template</th>
                            <th>Action</th>

                        </tr>
                        </tfoot>
                    </table>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>