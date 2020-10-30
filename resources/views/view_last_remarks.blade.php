
<div class="modal fade" id="remarks_returned{{$action_plan->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">Show Action Plan</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            {{-- <form  method='POST' action='approve-request/{{$issue->id}}' onsubmit='show()' enctype="multipart/form-data"> --}}
                <div class="modal-body">
                    @csrf
                    <label style="position:relative; top:7px;">Remarks:</label>
                    <p> {!! nl2br(e($action_plan->remarks))!!}</p>
                </div>
                
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form> --}}
        </div>
    </div>
</div>


