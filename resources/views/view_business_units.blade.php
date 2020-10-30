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
    @include('error')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"> </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id='business_units' class="table">
                            <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#new_bu" data-toggle="new_account"><i class='nc-icon nc-simple-add'></i> New Business Unit</button>
                            <thead class=" text-primary">
                                <th>
                                    Cluster
                                </th>
                                <th>
                                    Business Unit
                                </th>
                                <th>
                                    Code
                                </th>
                                <th>
                                    Cluster Head
                                </th>
                                <th>
                                    BU Head
                                </th>
                                <th>
                                    Managers
                                </th>
                                <th >
                                    Action
                                </th>
                            </thead>
                            <tbody>
                                @foreach($codes as $code)
                                <tr>
                                    <td>
                                        {{$code['cluster_info']->cluster_name}}
                                    </td>
                                    <td>
                                        {{$code->bu_name}}
                                    </td>
                                    <td>
                                        {{$code->bu_code}}
                                    </td>
                                    <td>
                                        @if($code['cluster_head_info'] != null){{$code['cluster_head_info']->first_name.' '.$code['cluster_head_info']->last_name}} @endif
                                    </td>
                                    <td>
                                        @if($code['bu_head_info'] != null){{$code['bu_head_info']->first_name.' '.$code['bu_head_info']->last_name}} @endif
                                    </td>
                                    <td>
                                        @php
                                            // $count_data = count($code['managers_data']);
                                            $array_manager = collect($code['managers_data']->pluck('manager_id'))->toArray();
                                        @endphp
                                            
                                        @foreach($code['managers_data'] as $manager)
                                            {{$manager['employee_info']->first_name.' '.$manager['employee_info']->last_name}}<br>
                                        @endforeach
                                    </td>
                                    <td>
                                        <button type="button"  href="#edit_bu{{$code->id}}" data-toggle="modal" title='Edit' class="btn btn-outline-dark">Edit</button>
                                    </td>
                                </tr>
                                @include('edit_bu')
                                @endforeach
                            </tbody>
                        </table>
                        <script  type="text/javascript">
                            
                            $(document).ready(function() {
                                $('#business_units').DataTable(
                                {
                                    // scrollX: true,
                                }
                                );
                            } );
                        </script>
                    </div>
                    @include('new_bu')
                </div>
            </div>
        </div>
        
    </div>
</div>

@endsection
