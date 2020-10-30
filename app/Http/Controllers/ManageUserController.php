<?php

namespace App\Http\Controllers;
Use App\User;
Use App\Account;
Use App\Role;
Use App\Employee;
Use App\ManageUser;
use Illuminate\Http\Request;

class ManageUserController extends Controller
{
    //
    public function viewManageAccount()
    {
        $roles = Role::orderBy('role_name','asc')->get();
        $role_array = collect($roles)->toArray();

        $accounts = Account::with('employee_info','employee_info.companies','employee_info.departments','manage_user.info_of_employee','manage_user.info_of_employee.departments','manage_user.info_of_employee.companies')
        ->where('role','like','%2%')
        ->orWhere('role','like','%3%')
        ->orWhere('role','like','%4%')
        ->get();
        
        return view('manage_account_users',array(
            'header' => "Manage Users",
            'roles' => $roles,
            'role_array' => $role_array,
            'accounts' => $accounts,
           
        ));
    }
    public function editManageAccount($account_id)
    {
      
        $accounts = Account::with('employee_info','employee_info.companies','employee_info.departments','manage_user.info_of_employee','manage_user.info_of_employee.departments','manage_user.info_of_employee.companies','manage_user.account_id')
        ->where('id','=',$account_id)
        ->first();
        
        $roles = json_decode($accounts->role);
        $manageAccountId = collect($accounts['manage_user']->pluck('user_id'))->toArray();
        // dd($manageAccountId);
        // dd($roles);
        if(in_array(4,$roles))
        {
            $newAccount = Account::with('employee_info','employee_info.companies','employee_info.departments')
            ->whereNotIn('user_id',$manageAccountId)
            ->where('id','!=',$account_id)
            ->where('role','like','%asdasd%')
           
            ->get();
        }
        if(in_array(3,$roles))
        {
            $newAccount = Account::with('employee_info','employee_info.companies','employee_info.departments')
            ->where('id','!=',$account_id)
            ->where('role','like','%4%')
            ->whereNotIn('user_id',$manageAccountId)
           
            ->get();
           
        }
        if(in_array(2,$roles))
        {
            $newAccount = Account::with('employee_info','employee_info.companies','employee_info.departments')
            ->where('id','!=',$account_id)
            ->whereNotIn('user_id',$manageAccountId)
            ->where(function($q) {
                $q->where('role','like','%3%')
                  ->orWhere('role','like','%4%');
            })
            ->get();
        }
        // dd($newAccount);

        return view('edit_manage_user',array(
            'header' => "Manage Users",
            'accounts' => $accounts,
            'newAccount' => $newAccount,
           
        ));
    }
    public function newManageUser(Request $request,$userId)
    {
        foreach($request->name as $name)
        {
            $manage_user = new Manageuser;
            $manage_user->user_id = $name;
            $manage_user->approver_id = $userId;
            $manage_user->add_by = auth()->user()->id;
            $manage_user->save();
        }
        $request->session()->flash('status','New Employee has been added!');
        return back();
    }
    public function removeManageUser(Request $request,$manageUserId)
    {
        $account = ManageUser::destroy($manageUserId);
        $request->session()->flash('status','Successfully Removed!');
         return back();
    }
}
