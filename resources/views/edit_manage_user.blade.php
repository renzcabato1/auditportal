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
        <div class="col-md-4">
            <div class="card card-user">
                <div class="image">
                    <img src="{{url('login_design/images/background2.jpg')}}" alt="...">
                </div>
                <div class="card-body">
                    <div class="author">
                        <a href="#">
                            <img class="avatar border-gray" src="{{'http://10.96.4.40:8441/hrportal/public/id_image/employee_image/'.$accounts->employee_info->id.'.png'}}" alt="...">
                            <h5 class="title"></h5>
                        </a>
                        <p class="description">
                            {{$accounts->employee_info->first_name.' '.$accounts->employee_info->last_name}}
                        </p>
                    </div>
                    <p class="description text-center">
                        {{$accounts['employee_info']['companies'][0]->name}}
                        <br>{{$accounts['employee_info']['departments'][0]->name}}
                        <br>
                        {{$accounts->employee_info->position}}
                    </p>
                </div>
                
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Employee <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#new_manage_user" data-toggle="new_account"><i class='nc-icon nc-simple-add'></i> New </button>
                    </h4>
                </div>
                <div class="card-body">
                    <table id='edit_user_table' class="table">
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
                                Action
                            </th>
                        </thead>
                        <tbody>
                            @foreach($accounts['manage_user'] as $manage_user)
                            <tr>
                                <td>
                                        <img class="logo-image-small avatar border-gray" src="{{'http://10.96.4.40:8441/hrportal/public/id_image/employee_image/'.$manage_user['info_of_employee']->id.'.png'}}">
                                </td>
                                <td>
                                        {{$manage_user['info_of_employee']->first_name.' '.$manage_user['info_of_employee']->last_name}}
                                </td>
                                <td>
                                        {{$manage_user['info_of_employee']['companies'][0]->name}}
                                </td>
                                <td>
                                        {{$manage_user['info_of_employee']['departments'][0]->name}}
                                </td>
                                <td>
                                       <a onclick='show()' href='remove-user/{{$manage_user->id}}'><button type="button" class="btn btn-outline-danger" title='remove'><i class='nc-icon nc-simple-remove'></i></button></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @include('new_manage_user')
                    <script  type="text/javascript">
                        $(document).ready(function() {
                            $('#edit_user_table').DataTable(
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

@endsection
