@extends('layouts.header')

@section('content')
@php
if(auth()->user()->team_id() != null)
{
    $roles = auth()->user()->team_id()->role;
    $roles_array = json_decode($roles);
}
@endphp
<div class="content">
    
    <div style='background-color:gray;padding:10px;'>
        <span style='color:white;font-size:25px;'>ITEMS FOR IMMEDIATE ACTION</span>
        
        <div class="row">
            @if((in_array(2,$roles_array)) || (in_array(3,$roles_array)) || (in_array(4,$roles_array)) )
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <a href="{{ url('/pending-issue') }}" onclick='show()'>
                        <div class="card card-stats">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-12 col-md-12">
                                        <div class="numbers">
                                            <p class="card-category">Request for Action Plans and Target Dates
                                            </p>
                                            <p class="card-title">{{$request_for_action_plan}}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <hr>
                                <div class="stats">
                                    <i class="fa fa-refresh"></i> 
                                    {{-- Update Now --}}
                                </div>
                            </div>
                        </div>
                        
                    </a>
                </div>
            @endif
            @if((in_array(3,$roles_array)) || (in_array(2,$roles_array)) )
            <div class="col-lg-3 col-md-6 col-sm-6">
                <a href="{{ url('/for-approval') }}" onclick='show()'>
                    <div class="card card-stats">
                        <div class="card-body ">
                            <div class="row">
                                
                                <div class="col-12 col-md-12">
                                    <div class="numbers">
                                        <p class="card-category">Action Plans for Approval
                                        </p>
                                        @php
                                        $total_for_approval = 0;
                                        @endphp
                                        @foreach($count_for_approvals as $for_approval)
                                        @if(($for_approval->issueBusinessUnit->bu_head == auth()->user()->id) && ($for_approval->last_status == "Pre-approved"))
                                        @php
                                        $total_for_approval = $total_for_approval;
                                        @endphp
                                        @else
                                        @php
                                        $total_for_approval = $total_for_approval + 1;
                                        @endphp
                                        @endif
                                        @endforeach
                                        <p class="card-title">{{$total_for_approval}}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer ">
                            <hr>
                            <div class="stats">
                                <i class="fa fa-calendar-o"></i> 
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endif
            @if((in_array(3,$roles_array)) || (in_array(2,$roles_array)) || (in_array(4,$roles_array))  )
            <div class="col-lg-3 col-md-6 col-sm-6">
                <a href="{{ url('/return-action-plans') }}" onclick='show()'>
                    <div class="card card-stats">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-5 col-md-4">
                                    <div class="icon-big text-center icon-warning">
                                        {{-- <i class="nc-icon nc-single-copy-04 text-danger"></i> --}}
                                    </div>
                                </div>
                                <div class="col-7 col-md-8">
                                    <div class="numbers">
                                        <p class="card-category">Returned Action Plans
                                        </p>
                                        <p class="card-title">{{$return_action_plans}}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer ">
                            <hr>
                            <div class="stats">
                                <i class="fa fa-clock-o"></i> 
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endif
            <div class="col-lg-3 col-md-6 col-sm-6">
                @if((in_array(2,$roles_array)) || (in_array(3,$roles_array)) || (in_array(4,$roles_array)) )
                
                <a href="{{ url('/action-plans') }}" onclick='show()'>
                    @else
                    <a href="{{ url('/for-audit') }}" onclick='show()'>
                        @endif
                        <div class="card card-stats">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-5 col-md-4">
                                        <div class="icon-big text-center icon-warning">
                                            {{-- <i class="nc-icon nc-check-2 text-primary"></i> --}}
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-8">
                                        <div class="numbers">
                                            @if((in_array(2,$roles_array)) || (in_array(3,$roles_array)) || (in_array(4,$roles_array)) )
                                            
                                            <p class="card-category"> Action Plan for Updates
                                            </p>
                                            @else
                                            <p class="card-category">Action Plans for Verification
                                            </p>
                                            @endif
                                            
                                            <p class="card-title">{{$actionplans_for_verification}}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <hr>
                                <div class="stats">
                                    <i class="fa fa-refresh"></i> 
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <br>
        @php
        $total_dues = 0;
        $total_not_dues = 0;
        @endphp
        @foreach($actionplans_dues as $actionplans_due)
        @php
        $date_today = date('Y-m-d');
        @endphp
        @if(($actionplans_due->target_date) <= $date_today)
        @php
        $total_dues = $total_dues + 1;
        @endphp
        @else
        @php
        $total_not_dues = $total_not_dues + 1;
        @endphp
        @endif
        @endforeach
        <div style='background-color:gray;padding:10px;'>
            <span style='color:white;font-size:25px;'>SUMMARY</span>
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <a href="{{ url('/action-plans-due') }}" onclick='show()'>
                        <div class="card card-stats">
                            <div class="card-body ">
                                <div class="row">
                                    
                                    <div class="col-12 col-md-12">
                                        <div class="numbers">
                                            <p class="card-category">ACTION PLANS 
                                                DUE
                                            </p>
                                            <p class="card-title"> {{$total_dues}} </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <hr>
                                <div class="stats">
                                    <i class="fa fa-refresh"></i> 
                                    {{-- Update Now --}}
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <a href="{{ url('/action-plans') }}" onclick='show()'>
                        <div class="card card-stats">
                            <div class="card-body ">
                                <div class="row">
                                    
                                    <div class="col-12 col-md-12">
                                        <div class="numbers">
                                            <p class="card-category">ACTION PLANS 
                                                NOT DUE
                                                
                                                
                                            </p>
                                            <p class="card-title">{{$total_not_dues}}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <hr>
                                <div class="stats">
                                    <i class="fa fa-calendar-o"></i> 
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <a href="{{ url('/action-plans') }}" onclick='show()'>
                        <div class="card card-stats">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-5 col-md-4">
                                        <div class="icon-big text-center icon-warning">
                                            {{-- <i class="nc-icon nc-single-copy-04 text-danger"></i> --}}
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-8">
                                        <div class="numbers">
                                            <p class="card-category">NEW ACTION PLANS
                                                
                                            </p>
                                            <p class="card-title">{{$new_action_plan}}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <hr>
                                <div class="stats">
                                    <i class="fa fa-clock-o"></i> this month
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <a href="{{ url('/verified-action-plans') }}" onclick='show()'>
                        <div class="card card-stats">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-5 col-md-4">
                                        <div class="icon-big text-center icon-warning">
                                            {{-- <i class="nc-icon nc-check-2 text-primary"></i> --}}
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-8">
                                        <div class="numbers">
                                            <p class="card-category">CLOSED ACTION PLANS
                                            </p>
                                            <p class="card-title">{{$closed_action_plans}}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <hr>
                                <div class="stats">
                                    <i class="fa fa-refresh"></i> this month
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    @endsection
    