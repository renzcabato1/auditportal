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
                            <thead class=" text-primary">
                                <th>
                                    Action Owner
                                </th>
                                <th>
                                    BU Responsible
                                </th>
                                <th>
                                    Priority Rating
                                </th>
                                {{-- <th>
                                    Audit Team
                                </th> --}}
                                <th>
                                    Engagement Title
                                </th>
                                <th >
                                    Issue Title
                                </th>
                                <th >
                                    Issue Description
                                </th>
                                <th >
                                    Recommendation
                                </th>
                                <th >
                                    Action
                                </th>
                            </thead>
                            <tbody>
                                {{-- {{$pendingIssues}} --}}
                                @foreach($pendingIssues as  $pendingIssue)
                                <tr>
                                    <td>
                                        {{$pendingIssue['ownerInfo']->name}}
                                    </td>
                                    <td>
                                        {{$pendingIssue['buCodeInfo']->bu_code}}
                                    </td>
                                    <td>
                                        {{$pendingIssue['ratingInfo']->rating_name}}
                                    </td>
                                    {{-- <td>
                                        {{$pendingIssue['IssueInfo']['team_name']->team_name}}
                                    </td> --}}
                                    <td>
                                        {{$pendingIssue['IssueInfo']->engagement_title}}
                                    </td>
                                    <td>
                                        {!! nl2br(e($pendingIssue['IssueInfo']->issue_title))!!}
                                    </td>
                                    <td>
                                        {!! nl2br(e($pendingIssue['IssueInfo']->issue))!!}
                                    </td>
                                    <td>
                                        {!! nl2br(e($pendingIssue['IssueInfo']->recommendation))!!}
                                    </td>
                                    
                                    @if(!$pendingIssue['actiontakenInfo']->isEmpty())
                                   
                                    <td>
                                        <button type="button" onclick='viewActionPlan({{$pendingIssue->id}})'  href="#viewActionPlan" data-toggle="modal" title='Submit Action Plan'  class="btn btn-outline-success btn-sm" >View Action Plan</button>
                                    </td>
                                    @else
                                    <td>
                                        <button type="button" onclick='getIssue({{$pendingIssue->id}})'  href="#actionPlan" data-toggle="modal" title='Submit Action Plan'  class="btn btn-outline-info btn-sm" >Submit Action Plan</button>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @include('actionPlan')
                        @include('viewActionPlan')
                        <script  type="text/javascript">
                            $(document).ready(function() {
                                $('#issues_view').DataTable(
                                {
                                    // scrollX: true,
                                }
                                );
                            } );
                            
                            function getIssue(pendingIssueId)
                            {
                                $("input").val("");
                                $("textarea").val("");
                                $('#pendingIssueId').val(pendingIssueId);
                                
                                // $('.action-plan').children().remove();
                                
                            }
                            function viewActionPlan(pendingIssueId)
                            {
                            document.getElementById("myDiv").style.display="block";
                                $.ajax({    //create an ajax request to load_page.php
                                    
                                    type: "GET",
                                    url: "{{ url('/get-action-plan') }}",            
                                    data: {
                                        "issueId" : pendingIssueId,
                                    }     ,
                                    dataType: "json",   //expect html to be returned
                                    success: function(data){ 
                                        document.getElementById("myDiv").style.display="none";
                                        $('.action-plan-view').children().remove();
                                        $("#pendingIssueIdView").val(pendingIssueId);
                                        jQuery.each(data, function(dataid) {

                                            if(data[dataid].action_status.status == 'Pending')
                                            {
                                                var resultView = "<div class='row mt-1' ><div class='col-md-8' id='"+data[dataid].id+"'>Action Plan: <span id='status-reply-"+data[dataid].id+"' class='small text-info'>"+data[dataid].action_status.status+"</span><textarea id='actionPlanTextarea"+data[dataid].id+"'  class='form-control' style='resize: vertical;max-height: 2000px;' name='action_plan_edit["+data[dataid].id+"]'  required>"+data[dataid].action_plan+"</textarea></div><div class='col-md-3'>Target Date : <input type='date' min='{{date('Y-m-d')}}' name='target_date_edit["+data[dataid].id+"]' id='actionPlanTargetDate"+data[dataid].id+"'  value="+data[dataid].target_date+"  class='form-control' required></div>@if(auth()->user()->id == "+data[dataid].action_by+")<div class='col-md-1' id='remove-button-"+data[dataid].id+"'><span class='form-control mt-4' ><a  onclick='removeactionplanview("+data[dataid].id+")' href='#' title='cancel' class='text-danger '>X</a></span></div>@endif</div>";
                                                $(".action-plan-view").append(resultView); 
                                            }
                                            else if(data[dataid].action_status.status == 'Cancelled')
                                            {
                                                var resultView = "<div class='row mt-1' ><div class='col-md-8'>Action Plan: <span id='status-reply-"+data[dataid].id+"' class='small text-danger'>"+data[dataid].action_status.status+" by "+data[dataid].action_status.status_by.name+" (<a title='"+data[dataid].action_status.remarks+"' href='' >Remarks</a>)</span><textarea id='actionPlanTextarea"+data[dataid].id+"'  class='form-control' style='resize: vertical;max-height: 2000px;' name='action_plan[]'  disabled>"+data[dataid].action_plan+"</textarea></div><div class='col-md-3'>Target Date : <input type='date' min='{{date('Y-m-d')}}' id='actionPlanTargetDate"+data[dataid].id+"' name='target_date[]' value="+data[dataid].target_date+"  class='form-control' disabled></div></div>";
                                                $(".action-plan-view").append(resultView);

                                            }
                                            else if(data[dataid].action_status.status == 'Pre-approved')
                                            {
                                                var resultView = "<div class='row mt-1' ><div class='col-md-8'>Action Plan: <span id='status-reply-"+data[dataid].id+"' class='small text-success'>"+data[dataid].action_status.status+" by "+data[dataid].action_status.status_by.name+"</span><textarea id='actionPlanTextarea"+data[dataid].id+"'  class='form-control' style='resize: vertical;max-height: 2000px;' name='action_plan[]'  disabled>"+data[dataid].action_plan+"</textarea></div><div class='col-md-3'>Target Date : <input type='date' min='{{date('Y-m-d')}}' id='actionPlanTargetDate"+data[dataid].id+"' name='target_date[]' value="+data[dataid].target_date+"  class='form-control' disabled></div></div>";
                                                $(".action-plan-view").append(resultView);

                                            }
                                            else if(data[dataid].action_status.status == 'Approved')
                                            {
                                                var resultView = "<div class='row mt-1' ><div class='col-md-8'>Action Plan: <span id='status-reply-"+data[dataid].id+"' class='small text-success'>"+data[dataid].action_status.status+" by "+data[dataid].action_status.status_by.name+"</span><textarea id='actionPlanTextarea"+data[dataid].id+"'  class='form-control' style='resize: vertical;max-height: 2000px;' name='action_plan[]'  disabled>"+data[dataid].action_plan+"</textarea></div><div class='col-md-3'>Target Date : <input type='date' min='{{date('Y-m-d')}}' id='actionPlanTargetDate"+data[dataid].id+"' name='target_date[]' value="+data[dataid].target_date+"  class='form-control' disabled></div></div>";
                                                $(".action-plan-view").append(resultView);

                                            }
                                        });
                                    },
                                    error: function(e)
                                    {
                                        alert(e);
                                    }
                                });
                            }
                            function removeactionplanview(id)
                            {
                                document.getElementById("myDiv").style.display="block";
                                $.ajax({    //create an ajax request to load_page.php
                                    
                                    type: "GET",
                                    url: "{{ url('/cancel-action-plan') }}",            
                                    data: {
                                        "id" : id,
                                    }     ,
                                    dataType: "json",   //expect html to be returned
                                    success: function(data){ 
                                        document.getElementById("myDiv").style.display="none";
                                        // document.getElementById("status-reply-"+data.status.actionplan_id+"").style.color = 'red';
                                        document.getElementById("status-reply-"+data.status.actionplan_id+"").innerHTML = "<span style='color:red'>"+data.status.status+" by "+data.name+"</span>";
                                        $("#remove-button-"+data.status.actionplan_id+"").remove();
                                        document.getElementById("actionPlanTextarea"+data.status.actionplan_id+"").disabled = true;
                                        document.getElementById("actionPlanTargetDate"+data.status.actionplan_id+"").disabled = true;
                                    },
                                    error: function(e)
                                    {
                                        alert(e);
                                    }
                                });
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
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
