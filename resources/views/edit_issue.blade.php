
    <div class="modal fade" id="edit_issue" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class='col-md-10'>
                        <h5 class="modal-title" id="exampleModalLabel">Edit Issue</h5>
                    </div>
                    <div class='col-md-2'>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <form method='POST' action='save-issue' onsubmit='show();'   >
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type='hidden' value='' id='issueIdedit' name='issueId'>
                        <div class='row'>
                            <div class='col-md-12'>
                                Engagement Title :
                                <input class='form-control' name='engagement_title' id='editEngagementTitle'  required>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-12'>
                                Issue Title:
                                <input class='form-control' name='issue_title' id='editIssueTitle'  required>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-12'>
                                Issue :
                                <textarea   class='form-control' style='resize: vertical;max-height: 2000px;min-height:100px;' name='issue' id='editIssue' required></textarea>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-12'>
                                Recommendation :
                                <textarea   class='form-control' style='resize: vertical;max-height: 2000px;min-height:100px;' name='recommendation' id='editRecommendation' required></textarea>
                            </div>
                        </div>
                        <div  class='edit-bu-responsible'>
                            
                        </div>
                        <div>
                            <div class='row'>
                                <div class='col-md-3'>
                                    <a onclick='EditNewBusinessUnit()' id='addBusinessUnit' class="btn btn-success" > + Add Business Unit</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type='submit'  class="btn btn-primary" id='editSubmitButton'  >Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function editViewAccount(Codeid)
        {
            
            var bu_code = document.getElementById("editbu-"+Codeid).value;
            $('#editActionOwner'+Codeid).empty();
            document.getElementById("myDiv").style.display="block";
            $.ajax({    //create an ajax request to load_page.php
                
                type: "GET",
                url: "{{ url('/get-process-owner/') }}",            
                data: {
                    "bu_code" : bu_code,
                }     ,
                dataType: "json",   //expect html to be returned
                success: function(data){    
                    document.getElementById("myDiv").style.display="none";
                    $('#editActionOwner'+Codeid).append('<option ></option>');
                    if(data.cluster_head != null)
                    {   
                        var cluster_head_role = "2";
                        $('#editActionOwner'+Codeid).append('<option value='+data.cluster_head+'-'+cluster_head_role+'  >'+ data.cluster_head_info.first_name + ' '+data.cluster_head_info.last_name + ' - CLUSTER HEAD</option>');
                    }
                    if(data.bu_head != null)
                    {
                        var bu_head_role = "3";
                        $('#editActionOwner'+Codeid).append('<option value='+ data.bu_head+'-'+bu_head_role+' >'+ data.bu_head_info.first_name + ' '+data.bu_head_info.last_name + ' - BU HEAD</option>');
                    }
                    if(data.managers_data != null)
                    {
                        var manager_head_role = "4";
                        jQuery.each(data.managers_data, function(id) {
                            //now you can access properties using dot notation
                            $('#editActionOwner'+Codeid).append('<option value='+ data.managers_data[id].manager_id+'-'+manager_head_role+' > '+ data.managers_data[id].employee_info.first_name+' '+  data.managers_data[id].employee_info.last_name +' - MANAGER</option>');
                        });
                    }
                },
                error: function(e)
                {
                    alert(e);
                }
            });
        }
        function EditNewBusinessUnit()
        {
            
            var idBusinessUnit = $('.edit-bu-responsible').children().last().attr('id');
            // alert(idBusinessUnit);
          
            var idBusinessUnit = parseInt(idBusinessUnit) + 1;
            var newBusinessUnit = "<div class='row mt-1' id='"+idBusinessUnit+"'><div class='col-md-5'>";
                newBusinessUnit += "<select class='form-control' id='bu-"+idBusinessUnit+"' onchange='viewAccount("+idBusinessUnit+")' name='bu_code_add[]' required><option></option>@foreach($bu_codes as $bu_code)<option value='{{$bu_code->id}}'>{{$bu_code->bu_name}} - {{$bu_code->bu_code}}</option>@endforeach</select></div>";
                newBusinessUnit += "<div class='col-md-4'><select class='form-control'  id='actionOwner"+idBusinessUnit+"' name='action_owner_add[]' required></select></div>";
                newBusinessUnit += "<div class='col-md-2'><select class='form-control' name='rating_add[]' required><option value=''></option>@foreach($ratings as $rating)<option value='{{$rating->id}}'>{{$rating->rating_name}}</option>@endforeach</select></div>";
                newBusinessUnit += "<div class='col-md-1'><span class='form-control' ><a  onclick='remove("+idBusinessUnit+")' href='#' class='text-danger'>X</a></span></div></div>";
                $(".edit-bu-responsible").append(newBusinessUnit);  
        }
        function remove(id)
        {
            // alert(id);   
            $("#"+id).remove();
        }
    </script>

        