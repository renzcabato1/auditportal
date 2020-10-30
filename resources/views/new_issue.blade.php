
<div>
    <div class="modal fade" id="new_issue" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class='col-md-10'>
                        <h5 class="modal-title" id="exampleModalLabel">New Issue</h5>
                    </div>
                    <div class='col-md-2'>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <form method='POST' action='new-issue' onsubmit='show();'   >
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <div class='row'>
                            <div class='col-md-12'>
                                Engagement Title :
                                <input class='form-control' name='engagement_title' required>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-12'>
                                Issue Title :
                                <input class='form-control' name='issue_title' required>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-12'>
                                Issue :
                                <textarea   class='form-control' style='resize: vertical;max-height: 2000px;min-height:100px;' name='issue' required></textarea>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-12'>
                                Recommendation :
                                <textarea   class='form-control' style='resize: vertical;max-height: 2000px;min-height:100px;' name='recommendation' required></textarea>
                            </div>
                        </div>
                        <div  class=' bu-responsible'>
                            <div class='row' id='1'>
                                <div class='col-md-5'>
                                    BU Responsible :
                                    <select class='form-control' onchange='viewAccount(1)' name='bu_code[]' id='bu-1' required>
                                        <option value=''></option>
                                        @foreach($bu_codes as $bu_code)
                                        <option value='{{$bu_code->id}}'>{{$bu_code->bu_name}} - {{$bu_code->bu_code}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class='col-md-4'>
                                    Action Owner :
                                    <select class='form-control' name='action_owner[]' id='actionOwner1' required>
                                        {{-- <option value=''></option>
                                        @foreach($accounts as $account)
                                        <option value='{{$account->user_id}}'>{{$account['employee_info']->first_name}} {{$account['employee_info']->last_name}}</option>
                                        @endforeach --}}
                                    </select>
                                </div>
                                <div class='col-md-3'>
                                    Priority Rating :
                                    <select class='form-control' name='rating[]' required>
                                        <option value=''></option>
                                        @foreach($ratings as $rating)
                                        <option value='{{$rating->id}}'>{{$rating->rating_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                               
                            </div>
                        </div>
                        <div>
                            <div class='row'>
                                <div class='col-md-3'>
                                    <a onclick='newBusinessUnit()'  class="btn btn-success" > + Add Business Unit</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type='submit'  class="btn btn-primary" >Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function viewAccount(Codeid)
        {
            
            var bu_code = document.getElementById("bu-"+Codeid).value;
            $('#actionOwner'+Codeid).empty();
            if(bu_code != "")
            {
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
                    $('#actionOwner'+Codeid).append('<option ></option>');
                    if(data.cluster_head != null)
                    {   
                        var cluster_head_role = "2";
                        $('#actionOwner'+Codeid).append('<option value='+data.cluster_head+'-'+cluster_head_role+'  >'+ data.cluster_head_info.first_name + ' '+data.cluster_head_info.last_name + ' - CLUSTER HEAD</option>');
                    }
                    if(data.bu_head != null)
                    {
                        var bu_head_role = "3";
                        $('#actionOwner'+Codeid).append('<option value='+ data.bu_head+'-'+bu_head_role+' >'+ data.bu_head_info.first_name + ' '+data.bu_head_info.last_name + ' - BU HEAD</option>');
                    }
                    if(data.managers_data != null)
                    {
                        var manager_head_role = "4";
                        jQuery.each(data.managers_data, function(id) {
                            //now you can access properties using dot notation
                            $('#actionOwner'+Codeid).append('<option value='+ data.managers_data[id].manager_id+'-'+manager_head_role+' > '+ data.managers_data[id].employee_info.first_name+' '+  data.managers_data[id].employee_info.last_name +' - MANAGER</option>');
                        });
                    }
                },
                error: function(e)
                {
                    alert(e);
                }
            });
            }
        }
        function newBusinessUnit()
        {
            var idBusinessUnit = $('.bu-responsible').children().last().attr('id');
            var idBusinessUnit = parseInt(idBusinessUnit) + 1;
            var newBusinessUnit = "<div class='row mt-1' id='"+idBusinessUnit+"'><div class='col-md-5'>";
                newBusinessUnit += "<select class='form-control' id='bu-"+idBusinessUnit+"' onchange='viewAccount("+idBusinessUnit+")' name='bu_code[]' required><option></option>@foreach($bu_codes as $bu_code)<option value='{{$bu_code->id}}'>{{$bu_code->bu_name}} - {{$bu_code->bu_code}}</option>@endforeach</select></div>";
                newBusinessUnit += "<div class='col-md-4'><select class='form-control'  id='actionOwner"+idBusinessUnit+"' name='action_owner[]' required></select></div>";
                newBusinessUnit += "<div class='col-md-2'><select class='form-control' name='rating[]' required><option value=''></option>@foreach($ratings as $rating)<option value='{{$rating->id}}'>{{$rating->rating_name}}</option>@endforeach</select></div>";
                newBusinessUnit += "<div class='col-md-1'><span class='form-control' ><a  onclick='remove("+idBusinessUnit+")' href='#' class='text-danger'>X</a></span></div></div>";
                $(".bu-responsible").append(newBusinessUnit);  
        }
        function remove(id)
        {
            $("#"+id).remove();
        }  
    </script>
        