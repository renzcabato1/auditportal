<?php

namespace App\Http\Controllers;
use App\Account;
use App\AuditTeam;
use Illuminate\Http\Request;

class AuditorController extends Controller
{
    //
    public function auditorView()
    {
        $teams = AuditTeam::orderBy('team_name','asc')->get();
        $accounts = Account::with('audit_info','employee_info','employee_info.companies','employee_info.departments')
        ->where('role','like','%5%')
        ->get();
        // dd($accounts);
        return view('view_auditors',
        array(
            'header' => "Auditors",
            'accounts' => $accounts,
            'teams' => $teams
        ));
    }
    public function saveEditAccount(Request $request,$accountId)
    {
        $account = Account::findOrfail($accountId);
        $account->team_id = $request->team;
        $account->position = $request->position;
        $account->save();
        $request->session()->flash('status','Successfully Changed!');
        return back();
    }
}
