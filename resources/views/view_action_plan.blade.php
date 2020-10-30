<div class="modal fade" id="view_action{{$issue->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">View Action Plan</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            
            <form  method='POST' action='edit-action-plan/{{$issue['action_plan'][0]->id}}' onsubmit="show()" enctype="multipart/form-data" >
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class='row'>
                        <div class='col-md-12'>
                            Action Plan :
                            <textarea  class='form-control' name='action_plan' required>{{$issue['action_plan'][0]->action_taken}}</textarea>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-12'>
                            Supporting Document/s :
                            @if(!($issue['action_plan'][0]['attachments_info']->isEmpty()))
                            <br>
                            @foreach($issue['action_plan'][0]['attachments_info'] as $attach)
                            <div id='{{$attach->id}}'><a href='{{URL::asset($attach->attachment_path)}}' target='_' ><span >{{$attach->attachment_name}}</span></a> </div>
                            @endforeach
                            @endif
                            {{-- <input name="attachment[]" class='form-control' placeholder="asdasd" type="file" multiple="multiple" /> --}}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    {{-- <button type="submit"  class="btn btn-primary" >Submit</button> --}}
                </div>
            </form>
        </div>
    </div>
</div>
