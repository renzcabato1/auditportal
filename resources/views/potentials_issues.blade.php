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
                                    BU Code
                                </th>
                                <th>
                                    Business Unit
                                </th>
                                <th >
                                    Issue Title
                                </th>
                                <th >
                                    Recommendation
                                </th>
                                <th >
                                   Cluster Head
                                </th>
                                <th >
                                    BU Head
                                </th>
                                <th >
                                    Created By
                                </th>
                            </thead>
                            <tbody>
                                @foreach($potentialissues as $potentialsIssue)
                                <tr>
                                    <td>{{$potentialsIssue->bu_code_info->bu_code}}</td>
                                    <td>{{$potentialsIssue->bu_code_info->bu_name}}</td>
                                    <td>{{$potentialsIssue->IssueInfo->issue_title}}</td>
                                    <td>{{$potentialsIssue->IssueInfo->recommendation}}</td>
                                    <td>@if($potentialsIssue->bu_code_info->cluster_head_info!= null) {{$potentialsIssue->bu_code_info->cluster_head_info->first_name.' '.$potentialsIssue->bu_code_info->cluster_head_info->last_name}} @endif</td>
                                    <td>@if($potentialsIssue->bu_code_info->bu_head_info!= null) {{$potentialsIssue->bu_code_info->bu_head_info->first_name.' '.$potentialsIssue->bu_code_info->bu_head_info->last_name}} @endif</td>
                                    <td>{{$potentialsIssue->userInfo->name}}</td>
                                        
                                        
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
<script  type="text/javascript">
    $(document).ready(function() {
        $('#issues_view').DataTable(
        {
            // scrollX: true,
        }
        );
    } );
</script>
@endsection
