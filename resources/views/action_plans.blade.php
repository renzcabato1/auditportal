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
                        <table id='action_plan' class="table">
                            <thead class=" text-primary">
                                <th >
                                    Date Created
                                </th>
                                <th>
                                    Action Owner
                                </th>
                                <th>
                                    BU Responsible
                                </th>
                                <th>
                                    Engagement Title
                                </th>
                                <th >
                                    Issue Title
                                </th>
                                <th >
                                    Issue
                                </th>
                                <th >
                                    Action Plans Approved
                                </th>
                                <th >
                                    Target Date
                                </th>
                                
                                <th >
                                    Update
                                </th>
                                <th >
                                    Remarks By Auditor
                                </th>
                            </thead>
                            <tbody>
                                @foreach($action_plans as $action_plan)
                                @php
                                $date_today = date('Y-m-d');
                                @endphp
                                @if(($action_plan->target_date) > $date_today)
                             
                                <tr>
                                    <td>
                                            {{date('M. d, Y h:i a',strtotime($action_plan->created_at))}}
                                    </td>
                                    <td>
                                            {{$action_plan->issueBusinessUnit->ownerInfo->name}}
                                    </td>
                                    <td>
                                            {{$action_plan->issueBusinessUnit->buCodeInfo->bu_name}}
                                    </td>
                                    <td>
                                            {!! nl2br(e($action_plan->issueBusinessUnit->issueInfo->engagement_title))!!}
                                    </td>
                                    <td >
                                            {!! nl2br(e($action_plan->issueBusinessUnit->issueInfo->issue_title))!!}
                                    </td>
                                    <td >
                                            {!! nl2br(e($action_plan->issueBusinessUnit->issueInfo->issue))!!}
                                    </td>
                                    <td >
                                            {!! nl2br(e($action_plan->action_plan))!!}
                                    </td>
                                   
                                    <td >
                                    
                                        {{date('M. d, Y',strtotime($action_plan->target_date))}}
                                    </td>
                                    <td>
                                        @if($action_plan->uploadProofDetails != null)
                                        <button type="button" onclick='viewallProof({{$action_plan->id}})' href="#viewproof" data-toggle="modal" title='View Proof'  class="btn btn-outline-success btn-sm" >View</button>
                                        @else
                                        <button type="button"  href="#proof{{$action_plan->id}}" data-toggle="modal" title='Upload Proof'  class="btn btn-outline-info btn-sm" >Upload Proof</button>
                                        @endif
                                    </td>
                                    <td>
                                        {!! nl2br(e($action_plan->remarks))!!}
                                    </td>
                                </tr>
                                @endif
                                @include('upload_proof')
                                {{-- @foreach($action_plan['actiontakenInfo'] as $key => $action)
                                
                                <tr>
                                    
                                    <td >
                                        {{date('M. d, Y H:i a',strtotime($action->created_at))}}
                                    </td>
                                    <td >{{$action_plan['ownerInfo']->name}}</td>
                                    <td >{{$action_plan['buCodeInfo']->bu_code}}</td>
                                    <td >{{$action_plan['ratingInfo']->rating_name}}</td>
                                    <td >{{$action_plan['issueInfo']->engagement_title}}</td>
                                    <td >{!! nl2br(e($action_plan['issueInfo']->issue))!!}</td>
                                    <td>
                                        {!! nl2br(e($action->action_plan))!!}
                                    </td>
                                    @if(($action->target_date) <= $date_today)
                                    <td style='background-color:red;color:white;'>
                                        @else
                                        <td >
                                            @endif
                                            {{date('M. d, Y',strtotime($action->target_date))}}
                                        </td>
                                        <td>
                                            @if($action['uploadProofDetails'] != null)
                                            <button type="button" onclick='viewallProof({{$action->id}})' href="#viewproof" data-toggle="modal" title='View Proof'  class="btn btn-outline-success btn-sm" >View</button>
                                            @else
                                            <button type="button"  href="#proof{{$action->id}}" data-toggle="modal" title='Upload Proof'  class="btn btn-outline-info btn-sm" >Upload Proof</button>
                                            @endif
                                        </td>
                                    </tr>
                                    @include('upload_proof')
                                    @endforeach --}}
                                    @endforeach
                                </tbody>
                            </table>
                            <script  type="text/javascript">
                                $(document).ready(function() {
                                    $('#action_plan').DataTable(
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
                                                var resultView = "<div id='"+data.upload_proof_details.attachment[dataid].id+"'><a href='"+url+"' target='_' ><span >"+data.upload_proof_details.attachment[dataid].attachment_name+"</span></a> <a href='javascript:void(0);' onclick='remove_attachment("+data.upload_proof_details.attachment[dataid].id+")' class='text-danger' title='Remove'><i class='nc-icon nc-simple-remove'></i></a></div>";
                                                $(".attachments").append(resultView);
                                            });
                                        },
                                        error: function(e)
                                        {
                                            alert(e);
                                        }
                                    });
                                }
                                function remove_attachment(proofID)
                                {
                                    document.getElementById("myDiv").style.display="block";
                                    $.ajax({    //create an ajax request to load_page.php
                                        type: "GET",
                                        url: "{{ url('/remove-attachment') }}",            
                                        data: 
                                        {
                                            "id" : proofID,
                                        }     ,
                                        dataType: "json",   //expect html to be returned
                                        success: function(data){ 
                                            document.getElementById("myDiv").style.display="none";
                                            document.getElementById(proofID).style.display="none";
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
        <script>
            function exportF(elem) {
                // var company_name =  document.getElementById('company_name').innerHTML;  
          
                var tab_text = "<table border='2px'><tr bgcolor='#87AFC6'>";
                    var textRange; var j = 0;
                    tab = document.getElementById('action_plan');//.getElementsByTagName('table'); // id of table
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
                        sa = txtArea1.document.execCommand("SaveAs", true, "action_plan.xls");
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
                            a.download =  "action_plan";
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
        @include('view_proof')
        @endsection
        