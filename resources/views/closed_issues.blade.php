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
                                    Issue
                                </th>
                                <th >
                                    Recommendation
                                </th>
                                <th >
                                    Closed By
                                </th>
                                <th >
                                    Date Closed
                                </th>
                                <th >
                                   Remarks
                                </th>
                                {{-- <th >
                                    Action
                                </th> --}}
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
                                    <td>
                                        {{$pendingIssue['IssueInfo']['team_name']->team_name}}
                                    </td>
                                    <td>
                                        {{$pendingIssue['IssueInfo']->engagement_title}}
                                    </td>
                                    <td>
                                        {!! nl2br(e($pendingIssue['IssueInfo']->issue))!!}
                                    </td>
                                    <td>
                                        {!! nl2br(e($pendingIssue['IssueInfo']->recommendation))!!}
                                    </td>
                                    <td>
                                        {!! nl2br(e($pendingIssue->closedBy->name))!!}
                                    </td>
                                    <td>
                                        {{date('M. d, Y',strtotime($pendingIssue->closed_date))}}
                                    </td>
                                    <td>
                                        {!! nl2br(e($pendingIssue->remarks))!!}
                                    </td>
                                    
                                    {{-- @if(!$pendingIssue['actiontakenInfo']->isEmpty())
                                   
                                    <td>
                                        <button type="button" onclick='viewActionPlan({{$pendingIssue->id}})'  href="#viewActionPlan" data-toggle="modal" title='Submit Action Plan'  class="btn btn-outline-success btn-sm" >View Action Plan</button>
                                    </td>
                                    @else
                                    <td>
                                        <button type="button" onclick='getIssue({{$pendingIssue->id}})'  href="#actionPlan" data-toggle="modal" title='Submit Action Plan'  class="btn btn-outline-info btn-sm" >Submit Action Plan</button>
                                    </td>
                                    @endif --}}
                                </tr>
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
