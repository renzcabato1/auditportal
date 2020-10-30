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
                                <th>
                                    Priority Rating
                                </th>
                                <th>
                                    Audit Team
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
                                    Recommendation
                                </th>
                                <th >
                                    Action Plan
                                </th>
                                <th >
                                    Action
                                </th>
                            </thead>
                            <tbody>
                                @foreach($issues as $issue)
                                    @if(($issue->issueBusinessUnit->bu_head == auth()->user()->id) && ($issue->last_status == "Pre-approved"))
                                    @else
                                        <tr>    
                                            <td>
                                                {{$issue->issueBusinessUnit->ownerInfo->name}}
                                            </td>
                                            <td>
                                                {{$issue->issueBusinessUnit->buCodeInfo->bu_name}}
                                            </td>
                                            <td>
                                                {{$issue->issueBusinessUnit->ratingInfo->rating_name}}
                                            </td>
                                            <td>
                                                    {{$issue->issueBusinessUnit->issueInfo->team_name->team_name}}
                                            </td>
                                            <td>
                                                    {!! nl2br(e($issue->issueBusinessUnit->issueInfo->engagement_title))!!}
                                            </td>
                                            <td >
                                                    {!! nl2br(e($issue->issueBusinessUnit->issueInfo->issue_title))!!}
                                            </td>
                                            <td >
                                                    {!! nl2br(e($issue->issueBusinessUnit->issueInfo->issue))!!}
                                            </td>
                                            <td >
                                                    {!! nl2br(e($issue->issueBusinessUnit->issueInfo->recommendation))!!}
                                            </td>
                                            <td >
                                                    {!! nl2br(e($issue->action_plan))!!}
                                            </td>
                                            <td >
                                            <a  data-toggle="modal" data-target="#approve-request{{$issue->id}}"  class="btn btn-outline-success btn-sm"  >Approve</button>
                                            <a  data-toggle="modal" data-target="#cancel-request{{$issue->id}}"  class="btn btn-outline-danger btn-sm" >Cancel</button>
                                            
                                            </td>
                                        </tr>
                                     @endif
                                @include('approve_action_plan')
                                @include('forApprovalActionPlan')
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
                         
                        </script>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

@endsection
