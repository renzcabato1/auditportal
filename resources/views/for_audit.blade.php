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
    <a id="btnExport" onclick="exportF(this)" class="btn btn-success btn-fill" style='margin-bottom:5px;'>Export to Excel</a><br>
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
                                <th>
                                    Engagement Title
                                </th>
                                <th >
                                    Issue
                                </th>
                                <th >
                                    Recommendation
                                </th>
                                <th >
                                    Action Plans
                                </th>
                                <th >
                                    Target Date
                                </th>
                                <th >
                                    Proof
                                </th>
                                <th>
                                    Team Name
                                </th>
                                <th >
                                    Action
                                </th>
                            </thead>
                            <tbody>
                                @foreach($actionPlans as $action_plan)
                                <tr>
                                    <td>
                                       
                                       {{$action_plan->issueBusinessUnit->ownerInfo->name}} 
                                    </td>
                                    
                                    <td>
                                            {{$action_plan->issueBusinessUnit->buCodeInfo->bu_name}}
                                    </td>
                                    <td>
                                            {{$action_plan->issueBusinessUnit->ratingInfo->rating_name}}
                                    </td>
                                    <td>
                                            {!! nl2br(e($action_plan->issueBusinessUnit->issueInfo->engagement_title))!!}
                                    </td>
                                    <td >
                                            {!! nl2br(e($action_plan->issueBusinessUnit->issueInfo->issue))!!}
                                    </td>
                                    <td >
                                            {!! nl2br(e($action_plan->issueBusinessUnit->issueInfo->recommendation))!!}
                                    </td>
                                    <td >
                                            {!! nl2br(e($action_plan->action_plan))!!}
                                    </td>
                                    <td >
                                            {{date('M. d, Y',strtotime($action_plan->target_date))}}
                                    </td>
                                    <td >
                                        @if($action_plan->uploadProofDetails != null)
                                        <button type="button" onclick='viewallProof({{$action_plan->id}})' href="#viewproof" data-toggle="modal" title='View Proof'  class="btn btn-outline-info btn-sm" >Attachments</button>
                                        @else
                                        No Attachment Uploaded
                                        @endif
                                    </td>
                                    <td>
                                        {{$action_plan->issueBusinessUnit->issueInfo->team_name->team_name}}
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-outline-success btn-sm mb-1" onclick='verifyRemarks({{$action_plan->id}})' data-toggle="modal" href="#remarksApproved">Verify Action Plan</button>
                                        <button type="button" class="btn btn-outline-danger btn-sm mb-1" onclick='returnRemarks({{$action_plan->id}})'  data-toggle="modal" href="#remarksReturn" >Return Action Plan</button>
                                        {{-- <button type="button" class="btn btn-outline-info btn-sm" onclick='returnRemarks({{$action_plan->id}})'  data-toggle="modal" href="#returnForUpdate" >Return Update</button> --}}
                                        @if($action_plan->auditor_action == "Returned")
                                        <button type="button" class="btn btn-outline-warning btn-sm mb-1" style='color:black;'  data-toggle="modal" href="#remarks_returned{{$action_plan->id}}">View last remarks</button>
                                        
                                        @endif
                                    </td>
                                </tr>
                                @include('view_last_remarks')
                            @endforeach
                        </tbody>
                    </table>
                    <script  type="text/javascript">
                        $(document).ready(function() {
                            $('#issues_view').DataTable(
                            {
                                // scrollX: true,
                            }
                            );
                        } );
                        
                        function viewallProof(actionID)
                        {
                            document.getElementById("myDiv").style.display="block";
                            $.ajax({    //create an ajax request to load_page.php
                                
                                type: "GET",
                                url: "{{ url('/view-proof-plan') }}",            
                                data: {
                                    "id" : actionID,
                                }     ,
                                dataType: "json",   //expect html to be returned
                                success: function(data){ 
                                    $('.attachments').children().remove();
                                    document.getElementById("myDiv").style.display="none";
                                    document.getElementById("remarksEdit").value = data.upload_proof_details.remarks;
                                    document.getElementById("proofIdData").value = data.upload_proof_details.id;
                                    jQuery.each(data.upload_proof_details.attachment, function(dataid) {
                                        
                                        var url = '{{ URL::asset('') }}'+data.upload_proof_details.attachment[dataid].attachment_path;
                                        var resultView = "<div id='"+data.upload_proof_details.attachment[dataid].id+"'><a href='"+url+"' target='_' ><span >"+data.upload_proof_details.attachment[dataid].attachment_name+"</span></a> </div>";
                                        $(".attachments").append(resultView);
                                    });
                                },
                                error: function(e)
                                {
                                    alert(e);
                                }
                            });
                        }
                        function returnRemarks(actionID)
                        {
                            
                            document.getElementById("myDiv").style.display="block";
                            document.getElementById("myDiv").style.display="none";
                            document.getElementById("actionplanId").value = actionID;
                          
                        }
                        function verifyRemarks(actionID)
                        {
                            
                            document.getElementById("myDiv").style.display="block";
                            document.getElementById("actionplanIdApproved").value = actionID;
                            document.getElementById("myDiv").style.display="none";
                            
                    
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
                                    sa = txtArea1.document.execCommand("SaveAs", true, "for_audit.xls");
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
                                        a.download =  "for_audit";
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
                </div>
            </div>
        </div>
    </div>
    @include('view_proof_for_audit')
    @include('remarks_return')
    @include('remarks_approved')
</div>
</div>

@endsection
