<?php

namespace App\Http\Controllers;
use App\Issue;
use App\Rating;
use App\Code;
use App\Account;
use App\Actionplan;
use App\IssueBusinessUnit;
use App\Action;
use App\StatusReport;
use App\ActionplanStatus;
use App\Attachment;
use App\Cluster;
use App\PotentialIssue;
use App\UploadProof;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    //
    public function viewIssues()
    {
        if(auth()->user()->team_id() != null)
        {
            $roles = auth()->user()->team_id()->role;
            $team  = auth()->user()->team_id()->team_id;
            $roles_array = json_decode($roles);
        }
        if(in_array(1,$roles_array) )
        {
        $issues = Issue::with(['team_name',
        'issueBusinessUnitInfo' => function ($query) {
            $query->where('status', '=', null);
        },
        'issueBusinessUnitInfo.buCodeInfo',
        'issueBusinessUnitInfo.ownerInfo',
        'issueBusinessUnitInfo.roleInfo',
        'issueBusinessUnitInfo.ratingInfo',
        'issueBusinessUnitInfo.buHeadInfo',
        'issueBusinessUnitInfo.clusterHeadInfo',
        'potentialIssues',
        ])
        ->whereHas('issueBusinessUnitInfo',function ($query) {
            $query->where('status','=',null);  
        })
        ->get();
        }
        else
        {
            $issues = Issue::with(['team_name',
            'issueBusinessUnitInfo' => function ($query) {
                $query->where('status', '=', null);
            },
            'issueBusinessUnitInfo.buCodeInfo',
            'issueBusinessUnitInfo.ownerInfo',
            'issueBusinessUnitInfo.roleInfo',
            'issueBusinessUnitInfo.ratingInfo',
            'issueBusinessUnitInfo.buHeadInfo',
            'issueBusinessUnitInfo.clusterHeadInfo',
            'potentialIssues',
            ])
            ->whereHas('issueBusinessUnitInfo',function ($query) {
                $query->where('status','=',null);  
            })
            ->where('created_team','=',$team)
            ->get();

        }
        // dd($issues);
        $ratings = Rating::get();
        $bu_codes = Code::orderBy('bu_name','asc')->get();
        
        return view('view_issues',
        array(
            'header' => "Issues",
            'issues' => $issues,
            'ratings' => $ratings,
            'bu_codes' => $bu_codes,
        ));

    }
    public function newIssue(Request $request)
    {
        // dd("'".$request->recommendation."'");
       $new_issue = new Issue;
       $new_issue->engagement_title = $request->engagement_title; 
       $new_issue->issue_title = $request->issue_title; 
       $new_issue->recommendation = $request->recommendation; 
       $new_issue->issue = $request->issue;
       $new_issue->created_by = auth()->user()->id;
       $new_issue->created_team = auth()->user()->team_id()->team_id;
       $new_issue->save();

       foreach($request->bu_code as $key => $bu_code)
       {
        $newBusinessUnitIssue = new IssueBusinessUnit;
        $newBusinessUnitIssue->issue_id = $new_issue->id;
        $newBusinessUnitIssue->rating_id = $request->rating[$key];
        $newBusinessUnitIssue->code_id = $bu_code;
        $name_explode = explode('-',$request->action_owner[$key]);
        $newBusinessUnitIssue->owner_id = $name_explode[0];
        $newBusinessUnitIssue->role_id = $name_explode[1];

        $approvers = Code::findOrfail($bu_code);
        $newBusinessUnitIssue->cluster_id = $approvers->cluster_head;
        $newBusinessUnitIssue->bu_head = $approvers->bu_head;
        $newBusinessUnitIssue->save();
       }
       $request->session()->flash('status','Successfully added');
       return back();
    }
    public function viewProcessOwner(Request $request)
    {
        $accounts = Code::with('cluster_head_info','bu_head_info','managers_data','managers_data.employee_info')
        ->where('id',$request->bu_code)
        ->first();
        return $accounts;
    }
    public function pendingIssue()
    {
        $pendingIssues = IssueBusinessUnit::with(['issueInfo','ownerInfo','buCodeInfo','ratingInfo','issueInfo.team_name','actiontakenInfo','actiontakenInfo.actionStatus'])
        ->where(function($q){
            $q->where('bu_head',auth()->user()->id)  
            ->orWhere('cluster_id',auth()->user()->id)  
            ->orWhere('owner_id',auth()->user()->id);
        })
        ->where('status','=',null)
        ->get();   
        return view('pending_issues',
        array(
            'header' => "Pending Issues",
            'pendingIssues' => $pendingIssues
        ));
    }
    public function forApproval()
    {
        $issues = Actionplan::
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
        return view('for_approval',
        array(
            'header' => "For Approval",
            'issues' => $issues,
        ));
    }
    public function removeIssue(Request $request,$issueId)
    {
        $issue = Issue::delete($issueId);
        $request->session()->flash('status','Successfully Removed');
        return back();
    }
    public function forAudit()
    {
        if(auth()->user()->team_id() != null)
        {
            $roles = auth()->user()->team_id()->role;
            $team  = auth()->user()->team_id()->team_id;
            $roles_array = json_decode($roles);
        }
        if(in_array(1,$roles_array) )
        {
            $actionPlan = Actionplan::where('last_status','=','Approved')
            ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','issueBusinessUnit.issueInfo.team_name')
            ->where(function($q) {
                $q->where('auditor_action','=',null);
                $q->orWhere('auditor_action','=',"Returned");
                })
            ->get();
            return view('for_audit',
            array(
                'header' => "For Audit",
                'actionPlans' => $actionPlan,
            ));
        }
        else
        {
            $actionPlan = Actionplan::where('last_status','=','Approved')
            ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','issueBusinessUnit.issueInfo.team_name')
            ->whereHas('issueBusinessUnit.issueInfo', function ($query) use ($team) {
                $query->where('created_team', '=', $team);
            })
            ->where(function($q) {
                $q->where('auditor_action','=',null);
                $q->orWhere('auditor_action','=',"Returned");
                })
            ->get();
            return view('for_audit',
            array(
                'header' => "For Audit",
                'actionPlans' => $actionPlan,
            ));
        }
        
    }
    public function getIssue(Request $request)
    {
        $issue = Issue::with('issueBusinessUnitInfo','issueBusinessUnitInfo.buCodeInfo','issueBusinessUnitInfo.buCodeInfo.cluster_head_info','issueBusinessUnitInfo.buCodeInfo.bu_head_info','issueBusinessUnitInfo.buCodeInfo.managers_data','issueBusinessUnitInfo.buCodeInfo.managers_data.employee_info')->where('id',$request->issueId)->first();
        return $issue;
    }
    public function saveEditIssue(Request $request)
    {
        $issue =  Issue::findOrfail($request->issueId);

        $issue->engagement_title = $request->engagement_title; 
        $issue->issue_title = $request->issue_title; 
        $issue->recommendation = $request->recommendation; 
        $issue->issue = $request->issue;
        $issue->save();
       foreach($request->bu_code as $key => $bu_code)
       {
        $edit_newBusinessUnitIssue = IssueBusinessUnit::findOrfail($key);
        $edit_newBusinessUnitIssue->rating_id = $request->rating[$key];
        $edit_newBusinessUnitIssue->code_id = $bu_code;
        $name_explode = explode('-',$request->action_owner[$key]);
        $edit_newBusinessUnitIssue->owner_id = $name_explode[0];
        $edit_newBusinessUnitIssue->role_id = $name_explode[1];

        $approvers = Code::findOrfail($bu_code);
        $edit_newBusinessUnitIssue->cluster_id = $approvers->cluster_head;
        $edit_newBusinessUnitIssue->bu_head = $approvers->bu_head;
        $edit_newBusinessUnitIssue->save();
        $data[] = $key;
       }
        $issueBusinessUnit = IssueBusinessUnit::where('issue_id',$request->issueId)->whereNotIn('id',$data)->delete();
       if($request->bu_code_add)
       {
        foreach($request->bu_code_add as $key => $bu_code_add)
        {
         $newBusinessUnitIssue = new IssueBusinessUnit;
         $newBusinessUnitIssue->rating_id = $request->rating_add[$key];
         $newBusinessUnitIssue->issue_id = $request->issueId;
         $newBusinessUnitIssue->code_id = $bu_code_add;
         $name_explode_add = explode('-',$request->action_owner_add[$key]);
         $newBusinessUnitIssue->owner_id = $name_explode_add[0];
         $newBusinessUnitIssue->role_id = $name_explode_add[1];
 
         $approvers = Code::findOrfail($bu_code_add);
         $newBusinessUnitIssue->cluster_id = $approvers->cluster_head;
         $newBusinessUnitIssue->bu_head = $approvers->bu_head;
         $newBusinessUnitIssue->save();
        }
       }
       $request->session()->flash('status','Successfully Updated');
       return back();
    }
    public function getAllForApproval(Request $request)
    {
        $action_plan = Actionplan::with('actionStatus','actionStatus.statusBy')->where('issue_business_unit_id',$request->issueId)->get();
        return $action_plan;
    }
    public function verifyActionPlan(Request $request)
    {
        $actionPlan = Actionplan::findOrfail($request->actionPlan);
        $actionPlan->auditor_action = "Verified";
        $actionPlan->audit_by = auth()->user()->id;
        $actionPlan->audit_date = date('Y-m-d');
        $actionPlan->remarks = $request->remarks;
        $actionPlan->save();
        $request->session()->flash('status','Successfully Verified');
        return back();
        
    }
    public function returnActionPlan(Request $request)
    {
        $actionPlan = Actionplan::findOrfail($request->actionPlan);
        $actionPlan->auditor_action = "Returned";
        $actionPlan->audit_by = auth()->user()->id;
        $actionPlan->audit_date = date('Y-m-d');
        $actionPlan->remarks = $request->remarks;
        $actionPlan->save();
        $request->session()->flash('status','Successfully Returned');
        return back();
    }
    public function verifyActionPlans()
    {
        if(auth()->user()->team_id() != null)
        {
            $roles = auth()->user()->team_id()->role;
            $team = auth()->user()->team_id()->team_id;
            $roles_array = json_decode($roles);
        }
        if((in_array(1,$roles_array)) )
        {
            $Issues = Actionplan::where('last_status','=','Approved')
            ->whereHas('issueBusinessUnit')
            ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','auditBy','issueBusinessUnit.issueInfo.team_name')
            ->where('auditor_action','=','Verified')
            ->get();
      
        }
        elseif((in_array(5,$roles_array)) )
        {
            $Issues = Actionplan::where('last_status','=','Approved')
            ->whereHas('issueBusinessUnit')
            ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','auditBy','issueBusinessUnit.issueInfo.team_name')
            ->whereHas('issueBusinessUnit.issueInfo', function ($query) use ($team) {
                $query->where('created_team', '=', $team);
            })
            ->where('auditor_action','=','Verified')
            ->get();
      
        }
        else
        {
            $Issues = Actionplan::where('last_status','=','Approved')
            ->whereHas('issueBusinessUnit',function ($query) {
                $query->where('bu_head',auth()->user()->id)  
                ->orWhere('cluster_id',auth()->user()->id)
                ->orWhere('owner_id',auth()->user()->id)
                ;
            })
            ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','auditBy','issueBusinessUnit.issueInfo.team_name')
            ->where('auditor_action','=','Verified')
            ->get();

        }
        return view('verified_action_plans',
        array(
            'header' => "Action Plan Verified",
            'actionPlans' => $Issues,
        ));
    }
    public function returnActionPlans()
    {
        $Issues = Actionplan::where('last_status','=','Approved')
        ->whereHas('issueBusinessUnit',function ($query) {
            $query->where('bu_head',auth()->user()->id)  
            ->orWhere('cluster_id',auth()->user()->id)
            ->orWhere('owner_id',auth()->user()->id)
            ;
        })
        ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','auditBy','issueBusinessUnit.issueInfo.team_name')
        ->where('auditor_action','=','Returned')
        ->get();
        return view('verified_action_plans',
        array(
            'header' => "Returned Action Plans",
            'actionPlans' => $Issues,
        ));
    }
    public function issueClosed(Request $request,$issueID)
    {
        foreach($request->issueID as $key => $issue)
        {
            if($request->type[$key] == "Open")
            {

                
            }
            else
            {
                $issueBusinessUnit = IssueBusinessUnit::findOrfail($issue);
                $issueBusinessUnit->status = "Closed";
                $issueBusinessUnit->closed_by = auth()->user()->id;
                $issueBusinessUnit->closed_date = date('Y-m-d');
                $issueBusinessUnit->remarks = $request->remarks[$key];
                $issueBusinessUnit->save();

            }
        }
        $request->session()->flash('status','Successfully Closed');
        return back();
    }
    public function ClosedIssues(){
       
        if(auth()->user()->team_id() != null)
        {
            $roles = auth()->user()->team_id()->role;
            $team = auth()->user()->team_id()->team_id;
            $roles_array = json_decode($roles);
        }
        // dd($roles_array);
        if((in_array(1,$roles_array)))
        {
            $closedIssues = IssueBusinessUnit::with(['issueInfo','ownerInfo','buCodeInfo','ratingInfo','issueInfo.team_name','actiontakenInfo','actiontakenInfo.actionStatus','closedBy'])
            ->where('status','!=',null)
            ->get();  
            
        }
        elseif((in_array(5,$roles_array)))
        {
            $closedIssues = IssueBusinessUnit::with(['issueInfo','ownerInfo','buCodeInfo','ratingInfo','issueInfo.team_name','actiontakenInfo','actiontakenInfo.actionStatus','closedBy'])
            ->whereHas('issueInfo', function ($query) use ($team) {
                $query->where('created_team', '=', $team);
            })
            ->where('status','!=',null)
            ->get();  
            
        }
        else
        {
            $closedIssues = IssueBusinessUnit::with(['issueInfo','ownerInfo','buCodeInfo','ratingInfo','issueInfo.team_name','actiontakenInfo','actiontakenInfo.actionStatus','closedBy'])
            ->where('status','!=',null)
            ->where(function($q){
                $q->where('bu_head',auth()->user()->id)  
                ->orWhere('cluster_id',auth()->user()->id)  
                ->orWhere('owner_id',auth()->user()->id);
            })
            ->get();  

        }
        return view('closed_issues',
        array(
            'header' => "Closed Issues",
            'pendingIssues' => $closedIssues
        ));
    }
    public function shareIssues (Request $request,$issueID)
    {
        if($request->bu_code)
        {
            $deleteIssue = PotentialIssue::where('issue_id',$issueID)->delete();
            foreach($request->bu_code as $bu_code)
            {
                $newPotentialIssue = new PotentialIssue;
                $newPotentialIssue->issue_id = $issueID;
                $newPotentialIssue->business_unit_id = $bu_code;
                $newPotentialIssue->created_by = auth()->user()->id;
                $newPotentialIssue->save();
                
            }
         
        }
        else
        {
            $deleteIssue = PotentialIssue::where('issue_id',$issueID)->delete();
           
        }
        $request->session()->flash('status','Successfully Shared');
        return back();
    }
    public function potentialIssues()
    {
        if(auth()->user()->team_id() != null)
        {
            $roles = auth()->user()->team_id()->role;
            $roles_array = json_decode($roles);
        }
        if((in_array(1,$roles_array)) || (in_array(5,$roles_array)) )
            {
            $potentialsIssue = PotentialIssue::
            with([
                'bu_code_info',
                'userInfo',
                'IssueInfo',
                'bu_code_info.cluster_head_info',
                'bu_code_info.bu_head_info'])
            ->get();
            }
        else
            {
            $potentialsIssue = PotentialIssue::
            with([
                'bu_code_info',
                'userInfo',
                'IssueInfo',
                'bu_code_info.cluster_head_info',
                'bu_code_info.bu_head_info'])
            ->whereHas('bu_code_info',function ($query) {
                $query->where('cluster_head',auth()->user()->id)  
                ->orWhere('bu_head',auth()->user()->id)
                ;
            })
            ->get();
            }

        return view('potentials_issues',
        array(
            'header' => "Potential Issues",
            'potentialissues' => $potentialsIssue,
        ));
    }
    public function viewSummaryIssues()
    {
        $codes = Code::with('cluster_info','issues_info.ownerInfo','issues_info.issueInfo')->withCount('issues_info')->orderBy('issues_info_count', 'desc')->get();
        $clusters = Cluster::with('codes_info','codes_info.issues_info')->get();
        // $employees = Account::whereHas('issues_info')->with('employee_info')->withCount('issues_info')->orderBy('issues_info_count', 'desc')->get();
        return view('summary_issues',
        array(
            'header' => "Summary Issues",
            'codes' => $codes,
            // 'employees' => $employees,
            'clusters' => $clusters,
        ));
    }
    public function viewIssuesCluster ()
    {
        $clusters = Cluster::with(['codes_info.issues_info'=> function ($query) {
            $query->where('status', '=', null);
        }])->get();
        $array = array(["Issues", "Total Issues"]);
        foreach($clusters as $cluster)
        {
            $total_issue = 0;
            foreach($cluster->codes_info as $clus)
            {
                $total_issue = $total_issue + count($clus->issues_info);
            }
            // $array = $array . "['" .$cluster->cluster_name ."',".$total_issue."],";

            array_push($array, [$cluster->cluster_name, $total_issue]);
        }
   
        return  $array;
        // return $array;
    }

    public function viewOpenClose()
    {
        $date_today = date('Y-m-d');
        $codes = Code::withCount(['issues_info' => function ($query) {
            $query->where('status', '=', null);
        },'issue_closed'=> function ($query) {
            $query->where('status', '!=', null);
        }])
        ->with(['issues_info.action_plan_not_due' => function ($query) use ($date_today){
            $query->where('last_status','=','Approved')
            ->where('target_date','>', $date_today)
            ->where(function($q) {
                $q->where('auditor_action','=',null);
                $q->orWhere('auditor_action','=',"Returned");
                })
                ->get();
            ;
        },])
        ->with(['issues_info.action_plan_due' => function ($query) use ($date_today){
            $query->where('last_status','=','Approved')
            ->where('target_date','<=', $date_today)
            ->where(function($q) {
                $q->where('auditor_action','=',null);
                $q->orWhere('auditor_action','=',"Returned");
                })
                ->get();
            ;
        },])
        ->whereHas('issues_info',function ($query) {
            $query->where('status','=',null);  
        })
        ->orderBy('issues_info_count', 'desc')
        ->get();

        return $codes;
    }
}
