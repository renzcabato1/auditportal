<?php

namespace App\Http\Controllers;
use App\Issue;
use App\Account;
use App\Action;
use App\Code;
use App\User;
use App\ActionPlan;
use App\BuManager;
use App\IssueBusinessUnit;
use App\Notifications\ManagerNotif;
use App\Notifications\ApproverNotif;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   

        if(auth()->user()->team_id() != null)
        {
            $roles = auth()->user()->team_id()->role;
            
            $team = auth()->user()->team_id()->team_id;
            $roles_array = json_decode($roles);
        }

        $date_first = date('Y-m-01');
        $date_last = date('Y-m-t');
        $request_for_action_plan = IssueBusinessUnit::with(['issueInfo','ownerInfo','buCodeInfo','ratingInfo','issueInfo.team_name','actiontakenInfo'])
        ->where(function($q){
            $q->where('bu_head',auth()->user()->id)  
            ->orWhere('cluster_id',auth()->user()->id)  
            ->orWhere('owner_id',auth()->user()->id);
        })
        ->whereDoesntHave('actiontakenInfo',function ($query){
            $query->where('last_status','=','Approved')
            ->orWhere('last_status','=','Pre-approved')
            ->orWhere('last_status','=','Pending');
        })
        ->where('status','=',null)
        ->count(); 

        $count_for_approvals = Actionplan::
        whereHas('issueBusinessUnit',function ($query) {
            $query->where('bu_head',auth()->user()->id)  
            ->orWhere('cluster_id',auth()->user()->id);
        })
        ->where(function($q){
            $q->where('last_status','=','Pre-approved')
              ->orWhere('last_status','=','Pending');
       })
        ->with([
            'actionStatus',
            'issueBusinessUnit',
            'issueBusinessUnit.ownerInfo',
            'issueBusinessUnit.buCodeInfo',
            'issueBusinessUnit.ratingInfo',
            'issueBusinessUnit.issueInfo',
            'uploadProofDetails',
            'issueBusinessUnit.issueInfo.team_name']
            )
        ->get();
        $return_action_plans = Actionplan::where('last_status','=','Approved')
        ->whereHas('issueBusinessUnit',function ($query) {
            $query->where('bu_head',auth()->user()->id)  
            ->orWhere('cluster_id',auth()->user()->id)
            ->orWhere('owner_id',auth()->user()->id)
            ;
        })
        ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','auditBy','issueBusinessUnit.issueInfo.team_name')
        ->where('auditor_action','=','Returned')
        ->count();
        if((in_array(1,$roles_array)))
        {
            $actionplans_for_verification = Actionplan::where('last_status','=','Approved')
            ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','issueBusinessUnit.issueInfo.team_name')
            
            ->where(function($q) {
                $q->where('auditor_action','=',null);
                $q->orWhere('auditor_action','=',"Returned");
                })
            ->count();

        }
        elseif((in_array(5,$roles_array)))
        {
            $actionplans_for_verification = Actionplan::where('last_status','=','Approved')
            ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','issueBusinessUnit.issueInfo.team_name')
            ->whereHas('issueBusinessUnit.issueInfo', function ($query) use ($team) {
                $query->where('created_team', '=', $team);
            })
            ->where(function($q) {
                $q->where('auditor_action','=',null);
                $q->orWhere('auditor_action','=',"Returned");
                })
            ->count();
           
        }
        else
        {
            $actionplans_for_verification = Actionplan::where('last_status','=','Approved')
            ->whereHas('issueBusinessUnit',function ($query) {
                $query->where('bu_head',auth()->user()->id)  
                ->orWhere('cluster_id',auth()->user()->id)
                ->orWhere('owner_id',auth()->user()->id)
                ;
            })
            ->where(function($q) {
                $q->where('auditor_action','=',null);
                $q->orWhere('auditor_action','=',"Returned");
                })
            ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','issueBusinessUnit.issueInfo.team_name')
            // ->where('auditor_action','=',null)
            ->count();

        }
       
        if((in_array(1,$roles_array)) )
        {
            $actionplans_dues = Actionplan::where('last_status','=','Approved')
            ->whereHas('issueBusinessUnit')
            ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','issueBusinessUnit.issueInfo.team_name')
        
            ->where(function($q) {
                $q->where('auditor_action','=',null);
                $q->orWhere('auditor_action','=',"Returned");
                })
            ->get();
        }
        elseif( (in_array(5,$roles_array)) )
        {
            $actionplans_dues = Actionplan::where('last_status','=','Approved')
            ->whereHas('issueBusinessUnit')
            ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','issueBusinessUnit.issueInfo.team_name')
            // ->where('auditor_action','=',null)
            ->whereHas('issueBusinessUnit.issueInfo', function ($query) use ($team) {
                $query->where('created_team', '=', $team);
            })
            
            ->where(function($q) {
                $q->where('auditor_action','=',null);
                $q->orWhere('auditor_action','=',"Returned");
                })
            ->get();
        }
        else
        {
            $actionplans_dues = Actionplan::where('last_status','=','Approved')
            ->whereHas('issueBusinessUnit',function ($query) {
                $query->where('bu_head',auth()->user()->id)  
                ->orWhere('cluster_id',auth()->user()->id)
                ->orWhere('owner_id',auth()->user()->id)
                ;
            })
            ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','issueBusinessUnit.issueInfo.team_name')
            ->where(function($q) {
                $q->where('auditor_action','=',null);
                $q->orWhere('auditor_action','=',"Returned");
                })
            ->get();
        }
      
        if((in_array(1,$roles_array)))
        {
            $closed_action_plans = Actionplan::where('last_status','=','Approved')
            ->whereHas('issueBusinessUnit')
            ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','auditBy','issueBusinessUnit.issueInfo.team_name')
            ->where('auditor_action','=','Verified')
            ->count();
       
        }
        elseif((in_array(5,$roles_array)))
        {
          
            $closed_action_plans = Actionplan::where('last_status','=','Approved')
            ->whereHas('issueBusinessUnit')
            ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','auditBy','issueBusinessUnit.issueInfo.team_name')
            ->whereHas('issueBusinessUnit.issueInfo', function ($query) use ($team) {
                $query->where('created_team', '=', $team);
            })
            ->where('auditor_action','=','Verified')
            ->count();
       
        }
        else
        {
            $closed_action_plans = Actionplan::where('last_status','=','Approved')
            ->whereHas('issueBusinessUnit',function ($query) {
                $query->where('bu_head',auth()->user()->id)  
                ->orWhere('cluster_id',auth()->user()->id)
                ->orWhere('owner_id',auth()->user()->id)
                ;
            })
            ->whereHas('issueBusinessUnit.issueInfo', function ($query) use ($team) {
                $query->where('created_team', '=', $team);
            })
            ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','auditBy','issueBusinessUnit.issueInfo.team_name')
            ->where('auditor_action','=','Verified')
            ->count();
        }
        if((in_array(1,$roles_array)) )
        {
        $new_action_plan = Actionplan::where('last_status','=','Approved')
        ->whereHas('issueBusinessUnit')
        ->whereBetween('created_at',[$date_first,$date_last])
        ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','issueBusinessUnit.issueInfo.team_name')
        ->where('auditor_action','=',null)
        ->count();
        }
        elseif( (in_array(5,$roles_array)) )
        {
        $new_action_plan = Actionplan::where('last_status','=','Approved')
        ->whereHas('issueBusinessUnit')
        ->whereBetween('created_at',[$date_first,$date_last])
        ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','issueBusinessUnit.issueInfo.team_name')
        ->where('auditor_action','=',null)
        ->whereHas('issueBusinessUnit.issueInfo', function ($query) use ($team) {
            $query->where('created_team', '=', $team);
        })
        ->count();
        }
        else
        {
            $new_action_plan = Actionplan::where('last_status','=','Approved')
            ->whereHas('issueBusinessUnit',function ($query) {
                $query->where('bu_head',auth()->user()->id)  
                ->orWhere('cluster_id',auth()->user()->id)
                ->orWhere('owner_id',auth()->user()->id)
                ;
            })
            ->whereBetween('created_at',[$date_first,$date_last])
            ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','issueBusinessUnit.issueInfo.team_name')
            ->where('auditor_action','=',null)
            ->count();
        }
        return view('home',array(
            'header' => "Dashboard",
            'request_for_action_plan' => $request_for_action_plan,
            'count_for_approvals' => $count_for_approvals,
            'return_action_plans' => $return_action_plans,
            'actionplans_for_verification' => $actionplans_for_verification,
            'closed_action_plans' => $closed_action_plans,
            'actionplans_dues' => $actionplans_dues,
            'new_action_plan' => $new_action_plan,
        ));
    }
    public function emailManual(Request $request)
    {
       $codes = Code::leftJoin('bu_managers','codes.id','=','bu_managers.bu_id')
       ->select('cluster_head','bu_head','bu_managers.manager_id')
       ->get()
       ->toArray();
        $accounts = [];
       foreach($codes as $code)
       {
           if($code['cluster_head'] != null)
           {
            $accounts[] =  $code['cluster_head'];
           }
           if($code['bu_head'] != null)
           {
            $accounts[] =  $code['bu_head'];
           }
           if($code['manager_id'] != null)
           {
            $accounts[] =  $code['manager_id'];
           }
       }
        $accounts = array_unique($accounts);
     foreach($accounts as $account)
     {
        $roles =  Account::where('user_id',$account)->first();
        if($roles != null)
        {   
            $team = $roles->team_id;
            $roles = $roles->role;
            $roles_array = json_decode($roles);
         
            $date_first = date('Y-m-01');
            $date_last = date('Y-m-t');
            $request_for_action_plan = IssueBusinessUnit::with(['issueInfo','ownerInfo','buCodeInfo','ratingInfo','issueInfo.team_name','actiontakenInfo'])
            ->where(function($q)  use ($account) {
                $q->where('bu_head',$account)  
                ->orWhere('cluster_id',$account)  
                ->orWhere('owner_id',$account);
            })
            ->whereDoesntHave('actiontakenInfo',function ($query){
                $query->where('last_status','=','Approved')
                ->orWhere('last_status','=','Pre-approved')
                ->orWhere('last_status','=','Pending');
            })
            ->where('status','=',null)
            ->count();
      
            $count_for_approvals = Actionplan::
                whereHas('issueBusinessUnit',function ($query) use ($account) {
                    $query->where('bu_head',$account)  
                    ->orWhere('cluster_id',$account);
                })
                ->where(function($q){
                    $q->where('last_status','=','Pre-approved')
                      ->orWhere('last_status','=','Pending');
               })
                ->with([
                    'actionStatus',
                    'issueBusinessUnit',
                    'issueBusinessUnit.ownerInfo',
                    'issueBusinessUnit.buCodeInfo',
                    'issueBusinessUnit.ratingInfo',
                    'issueBusinessUnit.issueInfo',
                    'uploadProofDetails',
                    'issueBusinessUnit.issueInfo.team_name']
                    )
                ->get();
            $total_for_approval = 0;
            foreach($count_for_approvals as $for_approval)
            {
            if(($for_approval->issueBusinessUnit->bu_head == $account) && ($for_approval->last_status == "Pre-approved"))
            
            $total_for_approval = $total_for_approval;
           
            else
           
            $total_for_approval = $total_for_approval + 1;
            
            }
            $return_action_plans = Actionplan::where('last_status','=','Approved')
            ->whereHas('issueBusinessUnit',function ($query) use ($account){
                $query->where('bu_head',$account)  
                ->orWhere('cluster_id',$account)
                ->orWhere('owner_id',$account)
                ;
            })
            ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','auditBy','issueBusinessUnit.issueInfo.team_name')
            ->where('auditor_action','=','Returned')
            ->count();
            if((in_array(1,$roles_array)))
            {
                $actionplans_for_verification = Actionplan::where('last_status','=','Approved')
                ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','issueBusinessUnit.issueInfo.team_name')
                
                ->where(function($q) {
                    $q->where('auditor_action','=',null);
                    $q->orWhere('auditor_action','=',"Returned");
                    })
                ->count();
    
            }
            elseif((in_array(5,$roles_array)))
            {
                $actionplans_for_verification = Actionplan::where('last_status','=','Approved')
                ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','issueBusinessUnit.issueInfo.team_name')
                ->whereHas('issueBusinessUnit.issueInfo', function ($query) use ($team) {
                    $query->where('created_team', '=', $team);
                })
                ->where(function($q) {
                    $q->where('auditor_action','=',null);
                    $q->orWhere('auditor_action','=',"Returned");
                    })
                ->count();
               
            }
            else
            {
                $actionplans_for_verification = Actionplan::where('last_status','=','Approved')
                ->whereHas('issueBusinessUnit',function ($query) use ($account) {
                    $query->where('bu_head',$account)  
                    ->orWhere('cluster_id',$account)
                    ->orWhere('owner_id',$account)
                    ;
                })
                ->where(function($q) {
                    $q->where('auditor_action','=',null);
                    $q->orWhere('auditor_action','=',"Returned");
                    })
                ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','issueBusinessUnit.issueInfo.team_name')
                // ->where('auditor_action','=',null)
                ->count();
    
            }
       
            if((in_array(1,$roles_array)) )
            {
                $actionplans_dues = Actionplan::where('last_status','=','Approved')
                ->whereHas('issueBusinessUnit')
                ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','issueBusinessUnit.issueInfo.team_name')
                ->where(function($q) {
                    $q->where('auditor_action','=',null);
                    $q->orWhere('auditor_action','=',"Returned");
                    })
                ->get();
            }
            elseif( (in_array(5,$roles_array)) )
            {
                $actionplans_dues = Actionplan::where('last_status','=','Approved')
                ->whereHas('issueBusinessUnit')
                ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','issueBusinessUnit.issueInfo.team_name')
                ->where(function($q) {
                    $q->where('auditor_action','=',null);
                    $q->orWhere('auditor_action','=',"Returned");
                    })
                ->whereHas('issueBusinessUnit.issueInfo', function ($query) use ($team) {
                    $query->where('created_team', '=', $team);
                })
                ->get();
            }
            else
            {
                $actionplans_dues = Actionplan::where('last_status','=','Approved')
                ->whereHas('issueBusinessUnit',function ($query) use ($account) {
                    $query->where('bu_head',$account)  
                    ->orWhere('cluster_id',$account)
                    ->orWhere('owner_id',$account)
                    ;
                })
                ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','issueBusinessUnit.issueInfo.team_name')
                ->where(function($q) {
                    $q->where('auditor_action','=',null);
                    $q->orWhere('auditor_action','=',"Returned");
                    })
                ->get();
            }
          
            if((in_array(1,$roles_array)))
            {
                $closed_action_plans = Actionplan::where('last_status','=','Approved')
                ->whereHas('issueBusinessUnit')
                ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','auditBy','issueBusinessUnit.issueInfo.team_name')
                ->where('auditor_action','=','Verified')
                ->count();
           
            }
            elseif((in_array(5,$roles_array)))
            {
              
                $closed_action_plans = Actionplan::where('last_status','=','Approved')
                ->whereHas('issueBusinessUnit')
                ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','auditBy','issueBusinessUnit.issueInfo.team_name')
                ->whereHas('issueBusinessUnit.issueInfo', function ($query) use ($team) {
                    $query->where('created_team', '=', $team);
                })
                ->where('auditor_action','=','Verified')
                ->count();
           
            }
            else
            {
                $closed_action_plans = Actionplan::where('last_status','=','Approved')
                ->whereHas('issueBusinessUnit',function ($query) use ($account) {
                    $query->where('bu_head',$account)  
                    ->orWhere('cluster_id',$account)
                    ->orWhere('owner_id',$account)
                    ;
                })
                ->whereHas('issueBusinessUnit.issueInfo', function ($query) use ($team) {
                    $query->where('created_team', '=', $team);
                })
                ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','auditBy','issueBusinessUnit.issueInfo.team_name')
                ->where('auditor_action','=','Verified')
                ->count();
            }
            if((in_array(1,$roles_array)) )
            {
            $new_action_plan = Actionplan::where('last_status','=','Approved')
            ->whereHas('issueBusinessUnit')
            ->whereBetween('created_at',[$date_first,$date_last])
            ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','issueBusinessUnit.issueInfo.team_name')
            ->where('auditor_action','=',null)
            ->count();
            }
            elseif( (in_array(5,$roles_array)) )
            {
            $new_action_plan = Actionplan::where('last_status','=','Approved')
            ->whereHas('issueBusinessUnit')
            ->whereBetween('created_at',[$date_first,$date_last])
            ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','issueBusinessUnit.issueInfo.team_name')
            ->where('auditor_action','=',null)
            ->whereHas('issueBusinessUnit.issueInfo', function ($query) use ($team) {
                $query->where('created_team', '=', $team);
            })
            ->count();
            }
            else
            {
                $new_action_plan = Actionplan::where('last_status','=','Approved')
                ->whereHas('issueBusinessUnit',function ($query) use ($account) {
                    $query->where('bu_head',$account)  
                    ->orWhere('cluster_id',$account)
                    ->orWhere('owner_id',$account)
                    ;
                })
                ->whereBetween('created_at',[$date_first,$date_last])
                ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','issueBusinessUnit.issueInfo.team_name')
                ->where('auditor_action','=',null)
                ->count();
            }
        }
        $total_dues = 0;
        $total_not_dues = 0;
        foreach($actionplans_dues as  $actionplans_due)
        {
            $date_today = date('Y-m-d');
            if(($actionplans_due->target_date) <= $date_today)
            {
                $total_dues = $total_dues + 1;
            }
            else
            {
                $total_not_dues = $total_not_dues + 1;
            }
        }
        if(in_array(4,$roles_array))
        {
            $user = User::findOrfail($account);
            $user->notify(new ManagerNotif($request_for_action_plan,$return_action_plans,$actionplans_for_verification,$total_dues,$total_not_dues,$new_action_plan,$closed_action_plans));

        }
        else
        {
            $user = User::findOrfail($account);
            $user->notify(new ApproverNotif($total_for_approval,$request_for_action_plan,$return_action_plans,$actionplans_for_verification,$total_dues,$total_not_dues,$new_action_plan,$closed_action_plans));
        }
     }
     $request->session()->flash('status','Successfully Emailed');
     return back();
    }
}
