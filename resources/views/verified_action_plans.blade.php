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
                                <th >
                                    Issue
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
                                <th>
                                    Audit By
                                </th>
                                <th>
                                    Audit Date
                                </th>
                                <th >
                                    Remarks
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
                                    <td >
                                            {!! nl2br(e($action_plan->issueBusinessUnit->issueInfo->issue))!!}
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
                                        {{$action_plan->auditBy->name}}
                                   
                                    </td>
                                    <td>
                                        {{date('M. d, Y',strtotime($action_plan->audit_date))}}
                                   
                                    </td>
                                    <td>
                                        {!! nl2br(e($action_plan->remarks))!!}
                                   
                                    </td>
                                </tr>
                                {{-- <tr>
                                    
                                    @foreach($action_plan['actiontakenInfo'] as $key => $action)
                                    
                                    
                                    <td >{{$action_plan['ownerInfo']->name}}</td>
                                    <td>{{$action_plan['buCodeInfo']->bu_code}}</td>
                                    <td >{{$action_plan['ratingInfo']->rating_name}}</td>
                                    <td>{{$action_plan['issueInfo']->engagement_title}}</td>
                                    <td>{!! nl2br(e($action_plan['issueInfo']->issue))!!}</td>
                                    <td>
                                        {!! nl2br(e($action->action_plan))!!}
                                    </td>
                                    <td >
                                        {{date('M. d, Y',strtotime($action->target_date))}}
                                    </td>
                                    <td>
                                        {{$action['actionStatus']->status}}
                                        @if($action['uploadProofDetails'] != null)
                                        <button type="button" onclick='viewallProof({{$action->id}})' href="#viewproof" data-toggle="modal" title='View Proof'  class="btn btn-outline-info btn-sm" >Attachments</button>
                                        @else
                                        No Attachment Uploaded
                                        @endif
                                    </td>
                                    <td >    {{$action_plan['issueInfo']['team_name']->team_name}}</td>
                                    <td>
                                    </td>
                                </tr> --}}
                                
                        
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                   
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@include('view_attachment')
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
    </script>
@endsection
