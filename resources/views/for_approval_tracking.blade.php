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
                    <div >
                        <table id='monitoring' class="table">
                            <thead class=" text-primary">
                                <th width='200px;'>
                                    Action Owner
                                </th>
                                <th>
                                    BU Responsible
                                </th>
                               
                                <th>
                                    Engagement Title
                                </th>
                                <th >
                                    Issue
                                </th>
                                <th >
                                    Action Plan
                                </th>
                                <th >
                                    Status
                                </th>
                               
                            </thead>
                            <tbody>
                                @foreach($issues as $issue)

                                        <tr>    
                                            <td style='width:200px;'>
                                            {{$issue->issueBusinessUnit->ownerInfo->name}}
                                            </td>
                                            <td style='width:200px;'>
                                                {{$issue->issueBusinessUnit->buCodeInfo->bu_name}}
                                            </td>
                                           
                                            <td style='width:200px;'>
                                                    {!! nl2br(e($issue->issueBusinessUnit->issueInfo->engagement_title))!!}
                                            </td>
                                            <td style='width:200px;'>
                                                    {!! nl2br(e($issue->issueBusinessUnit->issueInfo->issue))!!}
                                            </td>
                                            <td style='width:200px;'>
                                                    {!! nl2br(e($issue->action_plan))!!}
                                            </td>
                                            <td >
                                                {{$issue->last_status}}<br>
                                                Pre Approver : {{$issue->issueBusinessUnit->buHeadInfo->first_name." ".$issue->issueBusinessUnit->buHeadInfo->last_name}} <br>
                                                Approver :  {{$issue->issueBusinessUnit->clusterHeadInfo->first_name." ".$issue->issueBusinessUnit->clusterHeadInfo->last_name}}
                                           
                                            </td>
                                        </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <script  type="text/javascript">
                            $(document).ready(function() {
                                $('#monitoring').DataTable(
                                {
                                    // scrollY:        "300px",
                                    // scrollX:        true,
                                    scrollCollapse: true,
                                    paging:         true,
                                    fixedColumns:   {
                                        leftColumns: 1,
                                        rightColumns: 1
                                    },
                                    columnDefs: [
                                        { width: 200, targets: 0 }
                                    ],
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

@endsection
