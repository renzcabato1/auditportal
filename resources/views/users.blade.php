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
                        <table id='user_table' class="table">
                            <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#new_account" data-toggle="new_account"><i class='nc-icon nc-simple-add'></i> New User</button>
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
                                <th >
                                    Role
                                </th>
                                <th >
                                    Action
                                </th>
                            </thead>
                            <tbody>
                                @foreach($accounts as $account)
                                <tr>
                                    <td>
                                        <img  class="logo-image-small avatar border" src="{{'http://10.96.4.40:8441/hrportal/public/id_image/employee_image/'.$account->employee_info->id.'.png'}}">
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
                                    <td >
                                            @foreach(json_decode($account->role) as $role) 
                                            @php
                                                $key = array_search($role, array_column($role_array, 'id'));
                                            @endphp
                                            {{$roles[$key]->role_name}}
                                            <br>
                                            <br>
                                            @endforeach
                                    </td>
                                    <td >
                                            <button type="button"  href="#edit_account{{$account->id}}" data-toggle="modal" title='Edit'  class="btn btn-outline-info btn-sm" ><i class="nc-icon nc-ruler-pencil"></i> </button>
                                            {{-- <a href="reset-account/{{$account->id}}" onclick="javascript:return confirm('Are you sure you want to reset password ?')">
                                                <button type="button" class="btn btn-outline-success btn-sm" title='Reset'><i class="nc-icon nc-refresh-69"></i> </button>
                                            </a> --}}
                                            <a href="remove-account/{{$account->id}}" onclick="javascript:return confirm('Are you sure you want to remove this account ?')">
                                               
                                            <button type="button" class="btn btn-outline-danger btn-sm" title='Remove'><i class="nc-icon nc-simple-remove"></i> </button>
                                            </a>
                                    </td>
                                    @include('edit')
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <script  type="text/javascript">
       
                            $(document).ready(function() {
                                $('#user_table').DataTable(
                                    {
                                         // scrollX: true,
                                    }
                                );
                            } );
                        </script>
                    </div>
                </div>
                @include('new_account')
            </div>
        </div>
        
    </div>
</div>

@endsection
