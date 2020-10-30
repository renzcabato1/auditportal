<div class="modal fade" id="edit_account_auditor{{$account->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">Edit Account</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <form  method='POST' action='edit-account-auditor/{{$account->id}}' onsubmit="show()"  >
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class='row'>
                        <div class='col-md-12'>
                            Name :
                        <input  class='form-control' value = '{{$account->employee_info->first_name.' '.$account->employee_info->last_name}}'readonly>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-12'>
                            Team :
                            <select class='form-control' name="team" id='teams{{$account->id}}' required>
                                <option></option>
                                @foreach($teams as $team)
                                    <option value='{{$team->id}}' {{ (($team->id == $account->team_id) ? "selected":"") }}>{{$team->team_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-12'>
                            Role :
                            <select class='form-control' name="role">
                                <option value=''></option>
                                <option value='Team Manager' {{ (("Team Manager" == $account->positions) ? "selected":"") }}>Team Manager</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit"  class="btn btn-primary" >Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
