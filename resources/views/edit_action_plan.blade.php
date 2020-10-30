<div class="modal fade" id="edit_action_plan{{$issue->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">Edit Action Plan</h5>
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
                            <div id='{{$attach->id}}'><a href='{{URL::asset($attach->attachment_path)}}' target='_' ><span >{{$attach->attachment_name}}</span></a> @if($attach->attach_by ==  auth()->user()->id) <a href='javascript:void(0);' onclick='remove_attachment({{$attach->id}})' class="text-danger" title='Remove'><i class='nc-icon nc-simple-remove'></i></a>@endif</div>
                            @endforeach
                            @endif
                            <input name="attachment[]" class='form-control' placeholder="asdasd" type="file" multiple="multiple" />
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
<script>
    function remove_attachment(id)
    {
        
        document.getElementById("myDiv").style.display="block";
        $.ajax({    //create an ajax request to load_page.php
            
            type: "GET",
            url: "{{ url('/remove-attachment/') }}",            
            data: {
                "id" : id,
            }     ,
            dataType: "json",   //expect html to be returned
            success: function(id){    
                document.getElementById("myDiv").style.display="none";
                document.getElementById(id).style.display="none";
                document.getElementById('remove_notif').style.display="block";
            },
            error: function(e)
            {
                alert(e);
            }
        });
        
    }
</script>
