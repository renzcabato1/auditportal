<?php

namespace App\Http\Controllers;
Use App\User;
Use App\Account;
Use App\Role;
Use App\Employee;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function viewUsers()
    {
        $roles = Role::orderBy('role_name','asc')->get();
        $role_array = collect($roles)->toArray();

        $accounts = Account::with('employee_info','employee_info.companies','employee_info.departments')
        ->get();
        // dd ($accounts);
        $account_id = collect($accounts->pluck('user_id'))->toArray();
        $employees = Employee::with('companies','departments')
        ->where('status','!=','Inactive')
        ->whereNotIn('user_id',$account_id)
        ->orderBy(trim('first_name'),'asc')
        ->get();
        // return ($employees);
        
        return view('users',array(
            'header' => "Users",
            'roles' => $roles,
            'role_array' => $role_array,
            'employees' => $employees,
            'accounts' => $accounts,
        ));
        
    }
    public function changePassword(Request $request)
    {
        $this->validate(request(),[
            'password' => 'required|min:8|confirmed',
            ]    
        ); 
        $data =  User::find(auth()->user()->id);
        $data->password = bcrypt($request->input('password'));
        $data->save();
        $request->session()->flash('status','Your Password Successfully Changed!');
        return back();
    }
    public function newAccount(Request $request)
    {
        foreach($request->name as $name)
        {
        $account = new Account;
        $account->user_id = $name;
        $account->role = json_encode($request->roles);
        $account->save();
        }
        $request->session()->flash('status','New Account Successfully added!');
        return back();
    }
    public function editAccount(Request $request,$id)
    {
        $account = Account::findOrfail($id);
        $account->role = json_encode($request->roles);
        $account->save();
        $request->session()->flash('status','Successfully Changed!');
        return back();
    }
    public function resetPassword(Request $request,$id)
    {
        $data =  User::find($id);
        $data->password = bcrypt('12345678');
        $data->save();
        $request->session()->flash('status','New password : 12345678');
        return back();
    }
    public function removeAccount(Request $request,$id)
    {
        $account = Account::destroy($id);
        $request->session()->flash('status','Successfully Removed!');
         return back();
    }
}
