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
                        <table id='manage_user_table' class="table">
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
                                <th >
                                    Role
                                </th>
                                <th >
                                    Employee
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
                                        <td >
                                                @foreach(json_decode($account->role) as $role) 
                                                @php
                                                $key = array_search($role, array_column($role_array, 'id'));
                                                @endphp
                                                    {{$roles[$key]->role_name}}
                                                <br>
                                                @endforeach
                                        </td>
                                        <td >
                                               
                                                @foreach($account['manage_user'] as $manage_user)
                                                    @php
                                                    $image = $image + 1 ;
                                                    @endphp
                                                    <img class="logo-image-small avatar border-gray" style='border:solid 1px black' src="{{'http://10.96.4.40:8441/hrportal/public/id_image/employee_image/'.$manage_user['info_of_employee']->id.'.png'}}"  title='{{$manage_user['info_of_employee']->first_name.' '.$manage_user['info_of_employee']->last_name}}' alt="image">
                                                    @if($image == 5)
                                                        @php
                                                            break;
                                                        @endphp
                                                    @endif   
                                                @endforeach
                                                @if(count($account['manage_user']) > 5)
                                               <span class="logo-image-small avatar border-gray" tyle='background-color:gray;color:white;padding:5px;border-radius: 100%;font-size:12px;' title='
                                               <?php 
                                               foreach($account['manage_user'] as $key => $manage_user)
                                               { 
                                                   if($key > 4)
                                                   {
                                                        echo $manage_user['info_of_employee']->first_name .' '.$manage_user['info_of_employee']->last_name.'&#013;';
                                                    }
                                                } 
                                                ?>
                                                '> +{{count($account['manage_user']) - 5}}</span>
                                                @endif
                                        </td>
                                        <td >
                                                <a onclick='show()' href="manage-account-edit/{{$account->id}}" ><button type="button" class="btn btn-outline-dark">Edit</button></a>
                                        </td>
                                        
                                    </tr>
                                    @endforeach
                            </tbody>
                        </table>
                        <script  type="text/javascript">
                            
                            $(document).ready(function() {
                                $('#manage_user_table').DataTable(
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
