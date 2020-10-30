<div class="modal fade" id="remarksClosed{{$issue->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">Remarks</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            
            <form  method='POST' action='closed/{{$issue->id}}' onsubmit="show()" enctype="multipart/form-data" >
                <div class="modal-body">
                    {{ csrf_field() }}
                    @foreach($issue['issueBusinessUnitInfo']  as $key => $buCode)
                    <div class='row'>
                        <div class='col-md-4'>
                            {{$buCode['ownerInfo']->name}}
                            <input type='hidden' name="issueID[]" value='{{$buCode->id}}' >
                        </div>
                        <div class='col-md-4'>
                            <select name='type[]' class='form-control'>
                                <option value='Open'>Open</option>
                                <option value='Closed'>Closed</option>
                            </select>
                        </div>
                        <div class='col-md-4'>
                            <textarea name="remarks[]"  class='form-control' Placeholder='Remarks' ></textarea>
                        </div>
                    </div>
                    <hr>
                    @endforeach
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit"  class="btn btn-primary" >Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
