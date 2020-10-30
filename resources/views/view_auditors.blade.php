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
                        <table id='auditors_view' class="table">
                            {{-- <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#new_account" data-toggle="new_account"><i class='nc-icon nc-simple-add'></i> New User</button> --}}
                            <thead class=" text-primary">
                                <th>
                                    
                                </th>
                                <th>
                                    Name
                                </th>
                                <th>
                                    Company
                                </th>
                                <th>
                                    Department
                                </th>
                                <th>
                                    Audit Team
                                </th>
                                <th >
                                    Role
                                </th>
                                <th >
                                    Action
                                </th>
                            </thead>
                            <tbody>
                                    @foreach($accounts as $account)
                                    @php
                                        $image = 0;
                                    @endphp
                                    <tr>
                                        <td>
                                            <img class="logo-image-small avatar border-gray" src="{{'http://10.96.4.40:8441/hrportal/public/id_image/employee_image/'.$account->employee_info->id.'.png'}}">
                                        </td>
                                        <td>
                                            {{$account->employee_info->first_name.' '.$account->employee_info->last_name}}
                                        </td>
                                        <td>
                                            {{$account['employee_info']['companies'][0]->name}}
                                        </td>
                                        <td>
                                           {{$account['employee_info']['departments'][0]->name}}
                                        </td>
                                        <td>
                                            @if($account['audit_info'] != null)
                                                {{$account['audit_info']->team_name}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($account->position == null)
                                                Auditor
                                            @else
                                                {{$account->position}}
                                            @endif
                                        </td>
                                        <td >
                                          <button type="button"  href="#edit_account_auditor{{$account->id}}" data-toggle="modal" title='Edit' class="btn btn-outline-dark">Edit</button>
                                        </td>
                                    </tr>
                                    @include('edit_account_auditor')
                                    @endforeach
                            </tbody>
                        </table>
                        <script  type="text/javascript">
                            
                            $(document).ready(function() {
                                $('#auditors_view').DataTable(
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
