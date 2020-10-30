@extends('layouts.header')
@section('content')

<div class="content">
    @if(session()->has('status'))
    <div class="row">
        <div class="col-6">
            <div class="alert alert-success alert-with-icon alert-dismissible fade show" data-notify="container">
                <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="nc-icon nc-simple-remove"></i>
                </button>
                <span data-notify="icon" class="nc-icon nc-bell-55"></span>
                <span data-notify="message">{{session()->get('status')}}</span>
            </div>
        </div>
    </div>
    @endif
    <div class='row'>
        <div class='col-10'>
            <a id="btnExport" onclick="exportF(this)" class="btn btn-success btn-fill" style='margin-bottom:5px;'>Export to Excel</a> 
        </div>
        {{-- <div class='col-2'><a  onclick='show()' href='{{ url('/manual-email') }}' ><button type="button" class="btn btn-outline-info"> ✉ Manual Email</button></a> --}}
        <div class='col-2'><a  onclick='manual_email()'    ><button type="button" class="btn btn-outline-info"> ✉ Manual Email</button></a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"> </h4>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table id='issues_view' class="table">
                            <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#new_issue" data-toggle="new_issue"><i class='nc-icon nc-simple-add'></i> New Issue</button>
                            <thead class=" text-primary">
                                <th>
                                </th>
                                <th >
                                    Date Created 
                                </th>
                                <th width='200px;'>
                                    Audit Team
                                </th>
                                <th  width='200px;'>
                                    Engagement Title
                                </th>
                                <th  width='200px;'>
                                    Issue Title
                                </th>
                                <th >
                                    Recommendation
                                </th>
                             
                                <th width='300px' >
                                    Action Owner
                                </th>
                            </thead>
                            <tbody>
                                @foreach($issues as $issue)
                                
                                @include('remarksClosed')
                                @include('shareIssue')
                                <tr>
                                    <td >
                                        <button type="button" onclick='getIssue({{$issue->id}})'  href="#edit_issue" data-toggle="modal" title='Edit'  class="btn btn-outline-info btn-sm" >Action</button>
                                        <button type="button"  href="#remarksClosed{{$issue->id}}" data-toggle="modal" title='Close'  class="btn btn-outline-danger btn-sm" >Close</button>
                                        <button type="button"  href="#share{{$issue->id}}" data-toggle="modal" title='Share'  class="btn btn-outline-warning btn-sm" style='color:black;' >Share</button>
                                    </td>
                                    <td >
                                        {{date('M. d, Y h:i a',strtotime($issue->created_at))}}
                                    </td>
                                    <td>
                                        {{$issue['team_name']->team_name}}
                                    </td>
                                    <td >
                                        {{$issue->engagement_title}}
                                    </td>
                                    <td >
                                        {!! nl2br(e($issue->issue_title))!!}
                                    </td>
                                    <td >
                                        {!! nl2br(e($issue->recommendation))!!}
                                    </td>
                                    <td>
                                        @foreach($issue['issueBusinessUnitInfo']  as $key => $buCode)
                                        {{$buCode['buCodeInfo']->bu_name.' - '.$buCode['buCodeInfo']->bu_code}}
                                        <br>  
                                        {{$buCode['ownerInfo']->name}}
                                        <br>
                                        <p class="text-success">
                                            <small>{{$buCode['ratingInfo']->rating_name}}</small>
                                        </p>
                                        <hr>
                                        @endforeach
                                    </td>
                                </tr>
                                @endforeach
                                
                            </tr>
                        </tbody>
                    </table>
                    @include('new_issue')
                    @include('edit_issue')
                    <script  type="text/javascript">
                        $(document).ready(function() {
                            $('#issues_view').DataTable(
                            {
                                // scrollX: true,
                            }
                            );
                        } );
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    function getIssue(issueID)
    {
        document.getElementById("myDiv").style.display="block";
        $.ajax({    //create an ajax request to load_page.php
            
            type: "GET",
            url: "{{ url('/get-issue') }}",            
            data: {
                "issueId" : issueID,
            }     ,
            dataType: "json",   //expect html to be returned
            success: function(data){ 
                document.getElementById("myDiv").style.display="none";
                $('#editEngagementTitle').val(data.engagement_title);
                $('#editIssueTitle').val(data.issue_title);
                $('#editIssue').val(data.issue);
                $('#editRecommendation').val(data.recommendation);
                $('#issueIdedit').val(data.id);
                $('.edit-bu-responsible').children().remove();
                jQuery.each(data.issue_business_unit_info, function(businessUnitId) {
                    
                    var idBusinessUnit = $('.edit-bu-responsible').children().last().attr('id');
                    
                    if(isNaN(idBusinessUnit)){
                        var idBusinessUnit = parseInt(1);
                    }
                    else
                    {
                        var idBusinessUnit = parseInt(idBusinessUnit) + 1;
                    }
                    if(businessUnitId != 0)
                    {
                        //now you can access properties using dot notation
                      
                                var newBusinessUnit = "<div class='row mt-1' id='"+idBusinessUnit+"'><div class='col-md-5'>";
                                    newBusinessUnit += "<select  class='form-control' id='editbu-"+idBusinessUnit+"' onchange='editViewAccount("+idBusinessUnit+")' name='bu_code["+data.issue_business_unit_info[businessUnitId].id+"]' required><option></option>@foreach($bu_codes as $bu_code)<option value='{{$bu_code->id}}'  >{{$bu_code->bu_name}} - {{$bu_code->bu_code}}</option>@endforeach</select></div>";
                                    newBusinessUnit += "<div class='col-md-4'><select class='form-control'  id='editActionOwner"+idBusinessUnit+"' name='action_owner["+data.issue_business_unit_info[businessUnitId].id+"]' required></select></div>";
                                    newBusinessUnit += "<div class='col-md-2'><select class='form-control' id='editRating"+idBusinessUnit+"' name='rating["+data.issue_business_unit_info[businessUnitId].id+"]' required><option value=''></option>@foreach($ratings as $rating)<option value='{{$rating->id}}'>{{$rating->rating_name}}</option>@endforeach</select></div>";
                                    newBusinessUnit += "<div class='col-md-1'><span class='form-control' ><a  onclick='removeEditIssue("+idBusinessUnit+")' href='#' class='text-danger'>X</a></span></div></div>";
                                    $(".edit-bu-responsible").append(newBusinessUnit);  
                                    
                            }
                            else
                            {
                            
                        var newBusinessUnit = "<div class='row mt-1' id='"+idBusinessUnit+"'><div class='col-md-5'>";
                            newBusinessUnit += "<select class='form-control' id='editbu-"+idBusinessUnit+"' onchange='editViewAccount("+idBusinessUnit+")' name='bu_code["+data.issue_business_unit_info[businessUnitId].id+"]' required><option></option>@foreach($bu_codes as $bu_code)<option value='{{$bu_code->id}}'>{{$bu_code->bu_name}} - {{$bu_code->bu_code}}</option>@endforeach</select></div>";
                            newBusinessUnit += "<div class='col-md-4'><select class='form-control'  id='editActionOwner"+idBusinessUnit+"' name='action_owner["+data.issue_business_unit_info[businessUnitId].id+"]' required></select></div>";
                            newBusinessUnit += "<div class='col-md-3'><select class='form-control' id='editRating"+idBusinessUnit+"' name='rating["+data.issue_business_unit_info[businessUnitId].id+"]' required><option value=''></option>@foreach($ratings as $rating)<option value='{{$rating->id}}'>{{$rating->rating_name}}</option>@endforeach</select></div>";
                            newBusinessUnit += "</div>";
                            $(".edit-bu-responsible").append(newBusinessUnit);  
                            
                        }
                    $('#editActionOwner'+idBusinessUnit).append('<option ></option>');
                    if(data.issue_business_unit_info[businessUnitId].bu_code_info.cluster_head != null)
                    {   
                        var cluster_head_role = "2";
                        $('#editActionOwner'+idBusinessUnit).append('<option value='+data.issue_business_unit_info[businessUnitId].bu_code_info.cluster_head+'-'+cluster_head_role+'  >'+ data.issue_business_unit_info[businessUnitId].bu_code_info.cluster_head_info.first_name + ' '+data.issue_business_unit_info[businessUnitId].bu_code_info.cluster_head_info.last_name + ' - CLUSTER HEAD</option>');
                    }
                    if(data.issue_business_unit_info[businessUnitId].bu_code_info.bu_head != null)
                    {
                        var bu_head_role = "3";
                        $('#editActionOwner'+idBusinessUnit).append('<option value='+ data.issue_business_unit_info[businessUnitId].bu_code_info.bu_head+'-'+bu_head_role+' >'+ data.issue_business_unit_info[businessUnitId].bu_code_info.bu_head_info.first_name + ' '+data.issue_business_unit_info[businessUnitId].bu_code_info.bu_head_info.last_name + ' - BU HEAD</option>');
                    } 
                    if(data.issue_business_unit_info[businessUnitId].bu_code_info.managers_data != null)
                    {
                        var manager_head_role = "4";
                        jQuery.each(data.issue_business_unit_info[businessUnitId].bu_code_info.managers_data, function(id) {
                            //now you can access properties using dot notation
                            $('#editActionOwner'+idBusinessUnit).append('<option value='+ data.issue_business_unit_info[businessUnitId].bu_code_info.managers_data[id].manager_id+'-'+manager_head_role+' > '+ data.issue_business_unit_info[businessUnitId].bu_code_info.managers_data[id].employee_info.first_name+' '+  data.issue_business_unit_info[businessUnitId].bu_code_info.managers_data[id].employee_info.last_name +' - MANAGER</option>');
                        });
                    }
                    $("#editbu-"+idBusinessUnit).val(data.issue_business_unit_info[businessUnitId].code_id);
                    $("#editRating"+idBusinessUnit).val(data.issue_business_unit_info[businessUnitId].rating_id);
                    $("#editActionOwner"+idBusinessUnit).val(data.issue_business_unit_info[businessUnitId].owner_id+"-"+data.issue_business_unit_info[businessUnitId].role_id);
                });
            },
            function(e)
            {
                alert(e);
            }
        });
    }
    function removeEditIssue(id)
    {
        $("#"+id).remove();
    }
    function manual_email()
    {

        var result = confirm("Are you sure you want to send email manually?");
       if(result == true)
       {
        window.location.href = '{{ url('/manual-email') }}';
         show();
       }
       else
       {
           return false
       }
    }
</script>
 <script>
    function exportF(elem) {
        // var company_name =  document.getElementById('company_name').innerHTML;  
  
        var tab_text = "<table border='2px'><tr bgcolor='#87AFC6'>";
            var textRange; var j = 0;
            tab = document.getElementById('issues_view');//.getElementsByTagName('table'); // id of table
            if (tab==null) {
                return false;
            }
            if (tab.rows.length == 0) {
                return false;
            }
            
            for (j = 0 ; j < tab.rows.length ; j++) {
                tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
                //tab_text=tab_text+"</tr>";
            }
            
            tab_text = tab_text + "</table>";
            tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
            tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
            tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
            
            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE ");
            
            if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
            {
                txtArea1.document.open("txt/html", "replace");
                txtArea1.document.write(tab_text);
                txtArea1.document.close();
                txtArea1.focus();
                sa = txtArea1.document.execCommand("SaveAs", true, "issues.xls");
            }
            else                 //other browser not tested on IE 11
            //sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
            try {
                var blob = new Blob([tab_text], { type: "application/vnd.ms-excel" });
                window.URL = window.URL || window.webkitURL;
                link = window.URL.createObjectURL(blob);
                a = document.createElement("a");
                if (document.getElementById("caption")!=null) {
                    a.download=document.getElementById("caption").innerText;
                }
                else
                {
                    a.download =  "issues";
                }
                
                a.href = link;
                
                document.body.appendChild(a);
                
                a.click();
                
                document.body.removeChild(a);
            } catch (e) {
            }
            
            
            return false;
            //return (sa);
        }
    </script>
@endsection
                