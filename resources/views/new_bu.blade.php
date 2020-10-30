<div class="modal fade" id="new_bu" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">New Business Unit</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <style>
                
                #managers_chosen{
                    width: 100% !important;
                }
            </style>
            <form  method='POST' action='add-business' onsubmit='show();'  >
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class='row'>
                        <div class='col-md-12'>
                            Cluster :
                            <select class='form-control' name="cluster" required>
                                <option></option>
                                @foreach($clusters as $cluster)
                                <option value='{{$cluster->id}}'>{{$cluster->cluster_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-12'>
                            Business Unit:
                            <input class='form-control' name='bu_name' required>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-12'>
                            Code:
                            <input class='form-control' name='bu_code' required>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-12'>
                            Cluster Head:
                            <select class='form-control' name='cluster_head'>
                                <option value=''></option>
                                @foreach($cluster_heads as $cluster_head)
                                <option value='{{$cluster_head->user_id}}'>{{$cluster_head['employee_info']->first_name.' '.$cluster_head['employee_info']->last_name}}</option>
                                @endforeach
                            </select>
                            
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-12'>
                            BU Head:
                            <select class='form-control' name='bu_head'>
                                <option value=''></option>
                                @foreach($bu_heads as $bu_head)
                                <option value='{{$bu_head->user_id}}'>{{$bu_head['employee_info']->first_name.' '.$bu_head['employee_info']->last_name}}</option>
                                @endforeach
                            </select>
                            
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-12'>
                            Managers:
                            <select class='form-control chosen-select' name='managers[]' id='managers' multiple>
                                <option value=''></option>
                                @foreach($managers as $manager)
                                <option value='{{$manager->user_id}}'>{{$manager['employee_info']->first_name.' '.$manager['employee_info']->last_name}}</option>
                                @endforeach
                            </select>
                            
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id='' class="btn btn-primary" >Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
