<?php

namespace App\Http\Controllers;
use App\Action;
use App\Issue;
use App\StatusReport;
use App\Actionplan;
use App\IssueBusinessUnit;
use App\ActionplanStatus;
use App\Attachment;
use App\UploadProof;
use Illuminate\Http\Request;

class ActionController extends Controller
{
    //
    public function newAction(Request $request,$issueId)
    {
        $issue = Issue::findOrfail($issueId);
        
        if($issue->cluster_head == auth()->user()->id)
        {
            $status = "Approved";
            $issue->approved = 1;
            $issue->save();
        }
        else if($issue->bu_head == auth()->user()->id)
        {
            $status = "Pre-approved";
        }
        else 
        {
            $status = "Pending";
        }
        $action_plan = new Action;
        $action_plan->issue_id = $issueId;
        $action_plan->action_taken = $request->action_plan;
        $action_plan->status = $status;
        $action_plan->save();
        if($request->attachment != null)
        {
            foreach($request->attachment as $attachment)
            {
                $original_name = str_replace(' ', '',$attachment->getClientOriginalName());
                $name = time().'_'.$original_name;
                
                $attachment->move(public_path().'/attachment/', $name);
                $file_name = '/attachment/'.$name;
                $ext = pathinfo(storage_path().$file_name, PATHINFO_EXTENSION);
                
                $data = new Attachment;
                $data->action_id        = $action_plan->id;
                $data->attachment_name  = $original_name;
                $data->attachment_path  = $file_name ;
                $data->attach_by  = auth()->user()->id ;
                $data->save();
            }
        }
        $request->session()->flash('status','Successfully Submitted');
        return back();
        
    }  
    public function removeAttachment(Request $request)
    {
        $attachment = Attachment::destroy($request->id);
        return $request->id;
    }
    public function saveEditAction(Request $request,$actionId)
    {
        
        $action_plan = Action::findOrfail($actionId);
        $action_plan->action_taken = $request->action_plan;
        $action_plan->save();
        if($request->attachment != null)
        {
            foreach($request->attachment as $attachment)
            {
                
                $original_name = str_replace(' ', '',$attachment->getClientOriginalName());
                $name = time().'_'.$original_name;
                
                $attachment->move(public_path().'/attachment/', $name);
                $file_name = '/attachment/'.$name;
                $ext = pathinfo(storage_path().$file_name, PATHINFO_EXTENSION);
                
                $data = new Attachment;
                $data->action_id        = $action_plan->id;
                $data->attachment_name  = $original_name;
                $data->attachment_path  = $file_name ;
                $data->attach_by  = auth()->user()->id ;
                
                $data->save();
                
            }
        }
        $request->session()->flash('status','Successfully Updated');
        return back();
    }
    public function approvedActionPlan(Request $request, $issueId)
    {
        $issue = Issue::findOrfail($issueId);
        $action_plan = Action::where('issue_id',$issueId)->orderBy('id','desc')->first();
        
        if($issue->cluster_head == auth()->user()->id)
        {
            $status_report = new StatusReport;
            $status_report->action_id = $action_plan->id;
            $status_report->status = 'Approved';
            $status_report->status_by = auth()->user()->id;
            $status_report->status_date = date('Y-m-d');
            $status_report->save();
            $action_plan->status = 'Approved';
            $action_plan->save();
            $issue->approved = 1;
            $issue->save();
        }
        else
        {
            $status_report = new StatusReport;
            $status_report->action_id = $action_plan->id;
            $status_report->status = 'Pre-pproved';
            $status_report->status_by = auth()->user()->id;
            $status_report->status_date = date('Y-m-d');
            $status_report->save();
            $action_plan->status = 'Pre-approved';
            $action_plan->save();
        }
        $request->session()->flash('status','Successfully Approved');
        return back();
        
    }
    public function saveActionPlan(Request $request)
    {
        if($request->action_plan_edit != null)
        {
            foreach($request->action_plan_edit as $key => $edit_action_plan)
            {
                $action = Actionplan::findOrfail($key);
                $action->action_plan =  $edit_action_plan;
                $action->target_date =  $request->target_date_edit[$key];
                $action->save();
            }
        }
        if($request->action_plan != null)
        {
            foreach($request->action_plan as $key => $actionplan)
            {
                
                $issue = IssueBusinessUnit::findOrfail($request->pendingIssueId);
                
                if($issue->cluster_id == auth()->user()->id)
                {
                    $action = new Actionplan;
                    $action->issue_business_unit_id =  $request->pendingIssueId;
                    $action->action_plan =  $actionplan;
                    $action->target_date =  $request->target_date[$key];
                    $action->action_by =  auth()->user()->id;
                    $action->last_status =  "Approved";
                    $action->save();
                    $status = new ActionplanStatus;
                    $status->actionplan_id = $action->id;
                    $status->status = "Approved";
                    $status->action_by = auth()->user()->id;
                    $status->save();
                }
                else if($issue->bu_head == auth()->user()->id)
                {
                    $action = new Actionplan;
                    $action->issue_business_unit_id =  $request->pendingIssueId;
                    $action->action_plan =  $actionplan;
                    $action->target_date =  $request->target_date[$key];
                    $action->action_by =  auth()->user()->id;
                    $action->last_status =  "Pre-approved";
                    $action->save();
                    $status = new ActionplanStatus;
                    $status->actionplan_id = $action->id;
                    $status->status = "Pre-approved";
                    $status->action_by = auth()->user()->id;
                    $status->save();
                }
                else 
                {
                    $action = new Actionplan;
                    $action->issue_business_unit_id =  $request->pendingIssueId;
                    $action->action_plan =  $actionplan;
                    $action->target_date =  $request->target_date[$key];
                    $action->action_by =  auth()->user()->id;
                    $action->last_status =  "Pending";
                    $action->save();
                    $status = new ActionplanStatus;
                    $status->actionplan_id = $action->id;
                    $status->status = "Pending";
                    $status->action_by = auth()->user()->id;
                    $status->save();
                }
            }
        }
        $request->session()->flash('status','Successfully Submitted');
        return back();
    }
    public function getActionPlan(Request $request)
    {
        
        $action_plan = Actionplan::with('actionStatus','actionStatus.statusBy')->where('issue_business_unit_id',$request->issueId)->get();
        return $action_plan;
    }
    public function cancelActionPlan(Request $request)
    {
        $status = new ActionplanStatus;
        $status->actionplan_id = $request->id;
        $status->status = "Cancelled";
        $status->action_by = auth()->user()->id;
        $status->save();
        $name = auth()->user()->name;
        $data1 = [
            'name' =>  $name,
            'status' => $status,
        ];
        return $data1;
    }
    public function cancelRequest(Request $request,$requestId)
    {
        $status = new ActionplanStatus;
        $status->actionplan_id = $requestId;
        $status->status = "Cancelled";
        $status->remarks = $request->remarks;
        $status->action_by = auth()->user()->id;
        $status->save();
        $actionPlanStatus = Actionplan::findOrfail($requestId);
        $actionPlanStatus->last_status = "Cancelled";
        
        $actionPlanStatus->save();
        $request->session()->flash('status','Successfully Cancelled');
        return back();
    }
    public function approveActionPlan(Request $request)
    { 
        
        $actionPlan = Actionplan::findOrfail($request->id);
        $issue = IssueBusinessUnit::findOrfail($actionPlan->issue_business_unit_id);
        if($issue->cluster_id == auth()->user()->id)
        {
            $status = new ActionplanStatus;
            $status->actionplan_id = $request->id;
            $status->status = "Approved";
            $status->action_by = auth()->user()->id;
            $status->save();
        }
        else if($issue->bu_head == auth()->user()->id)
        {
            
            $status = new ActionplanStatus;
            $status->actionplan_id = $request->id;
            $status->status = "Pre-approved";
            $status->action_by = auth()->user()->id;
            $status->save();
        }
        else 
        {
            $status = new ActionplanStatus;
            $status->actionplan_id = $request->id;
            $status->status = "Pending";
            $status->action_by = auth()->user()->id;
            $status->save();
        }
        $name = auth()->user()->name;
        $data1 = [
            'name' =>  $name ,
            'status' => $status,
        ];
        return $data1;
    }
    public function approveActionPlans(Request $request,$requestID)
    { 
        
        $actionPlan = Actionplan::findOrfail($requestID);
        $issue = IssueBusinessUnit::findOrfail($actionPlan->issue_business_unit_id);
        if($issue->cluster_id == auth()->user()->id)
        {
            $status = new ActionplanStatus;
            $status->actionplan_id = $requestID;
            $status->status = "Approved";
            $status->action_by = auth()->user()->id;
            $status->save();
            $actionPlan->last_status =  "Approved";
            $actionPlan->action_plan =  $request->action_plan;
            $actionPlan->save();
        }
        else if($issue->bu_head == auth()->user()->id)
        {
            
            $status = new ActionplanStatus;
            $status->actionplan_id = $requestID;
            $status->status = "Pre-approved";
            $status->action_by = auth()->user()->id;
            
            $status->save();
            $actionPlan->last_status =  "Pre-approved";
            $actionPlan->action_plan =  $request->action_plan;
            $actionPlan->save();
        }
        else 
        {
            $status = new ActionplanStatus;
            $status->actionplan_id = $requestID;
            $status->status = "Pending";
            $status->action_by = auth()->user()->id;
            $status->save();
            $actionPlan->last_status =  "Pending";
            $actionPlan->action_plan =  $request->action_plan;
            $actionPlan->save();
        }
        $request->session()->flash('status','Successfully Approved');
        return back();
    }
    public function allActionPlans ()
    {
        if(auth()->user()->team_id() != null)
        {
            $roles = auth()->user()->team_id()->role;
            $team = auth()->user()->team_id()->team_id;
            $roles_array = json_decode($roles);
        }
        if((in_array(2,$roles_array)) || (in_array(3,$roles_array)) || (in_array(4,$roles_array)) )
        {
            $Issues = Actionplan::where('last_status','=','Approved')
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
        elseif((in_array(1,$roles_array)))
        {
            $Issues = Actionplan::where('last_status','=','Approved')
            ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','issueBusinessUnit.issueInfo.team_name')
            ->where(function($q) {
                $q->where('auditor_action','=',null);
                $q->orWhere('auditor_action','=',"Returned");
            })
            ->get();
        }
        elseif((in_array(5,$roles_array)))
        {
            $Issues = Actionplan::where('last_status','=','Approved')
            ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','issueBusinessUnit.issueInfo.team_name')
            ->whereHas('issueBusinessUnit.issueInfo', function ($query) use ($team) {
                $query->where('created_team', '=', $team);
            })
            ->where(function($q) {
                $q->where('auditor_action','=',null);
                $q->orWhere('auditor_action','=',"Returned");
            })
            ->get();
        }
        return view('action_plans',
        array(
            'header' => "Action Plans",
            'action_plans' => $Issues,
        ));  
    }
    public function actionPlanDue ()
    {
        if(auth()->user()->team_id() != null)
        {
            $roles = auth()->user()->team_id()->role;
            $team = auth()->user()->team_id()->team_id;
            $roles_array = json_decode($roles);
        }
        if((in_array(2,$roles_array)) || (in_array(3,$roles_array)) || (in_array(4,$roles_array)) )
        {
            $Issues = Actionplan::where('last_status','=','Approved')
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
        elseif((in_array(1,$roles_array)))
        {
            $Issues = Actionplan::where('last_status','=','Approved')
            ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','issueBusinessUnit.issueInfo.team_name')
            ->where(function($q) {
                $q->where('auditor_action','=',null);
                $q->orWhere('auditor_action','=',"Returned");
            })
            ->get();
        }
        elseif((in_array(5,$roles_array)))
        {
            $Issues = Actionplan::where('last_status','=','Approved')
            ->with('actionStatus','issueBusinessUnit','issueBusinessUnit.ownerInfo','issueBusinessUnit.buCodeInfo','issueBusinessUnit.ratingInfo','issueBusinessUnit.issueInfo','uploadProofDetails','issueBusinessUnit.issueInfo.team_name')
            ->whereHas('issueBusinessUnit.issueInfo', function ($query) use ($team) {
                $query->where('created_team', '=', $team);
            })
            ->where(function($q) {
                $q->where('auditor_action','=',null);
                $q->orWhere('auditor_action','=',"Returned");
            })
            ->get();
        }
        return view('action_plans_due',
        array(
            'header' => "Action Plans Due",
            'action_plans' => $Issues,
        ));  
    }
    public function uploadProof(Request $request,$actionId)
    {
        $upload_proof = new UploadProof; 
        $upload_proof->actionplan_id = $actionId;
        $upload_proof->remarks = $request->remarks;
        $upload_proof->save();
        if($request->hasFile('attachment'))
        {
            foreach($request->attachment as $attachment)
            {
                $original_name = str_replace(' ', '',$attachment->getClientOriginalName());
                $name = time().'_'.$original_name;
                
                $attachment->move(public_path().'/attachment/', $name);
                $file_name = '/attachment/'.$name;
                $ext = pathinfo(storage_path().$file_name, PATHINFO_EXTENSION);
                
                $data = new Attachment;
                $data->upload_proof_id  = $upload_proof->id;
                $data->attachment_name  = $original_name;
                $data->attachment_path  = $file_name ;
                $data->attach_by  = auth()->user()->id ;
                $data->save();
            }
        }
        $request->session()->flash('status','Successfully Uploaded Proof');
        return back();
        
    }
    public function viewProofPlan(Request $request)
    {
        $actionPlan = Actionplan::with('uploadProofDetails','uploadProofDetails.attachment')->where('id',$request->id)->first();
        return $actionPlan;
    }
    public function updateProof(Request $request)
    {
        $upload_proof = UploadProof::findOrfail($request->proofId); 
        $upload_proof->remarks = $request->remarks;
        $upload_proof->save();
        if ($request->hasFile('attachment'))
        {
            foreach($request->attachment as $attachment)
            {
                $original_name = str_replace(' ', '',$attachment->getClientOriginalName());
                $name = time().'_'.$original_name;
                
                $attachment->move(public_path().'/attachment/', $name);
                $file_name = '/attachment/'.$name;
                $ext = pathinfo(storage_path().$file_name, PATHINFO_EXTENSION);
                
                $data = new Attachment;
                $data->upload_proof_id        = $upload_proof->id;
                $data->attachment_name  = $original_name;
                $data->attachment_path  = $file_name ;
                $data->attach_by  = auth()->user()->id ;
                $data->save();
                
            }
        }
        $request->session()->flash('status','Successfully Upload Proof');
        return back();
        
    }
    public function monitoring(Request $request)
    {
        $issues = Actionplan::where(function($q){
            $q->where('last_status','=','Pre-approved')
              ->orWhere('last_status','=','Pending');
       })
       ->whereHas('issueBusinessUnit')
        ->with([
            'actionStatus',
            'issueBusinessUnit',
            'issueBusinessUnit.ownerInfo',
            'issueBusinessUnit.buCodeInfo',
            'issueBusinessUnit.buHeadInfo',
            'issueBusinessUnit.clusterHeadInfo',
            'issueBusinessUnit.ratingInfo',
            'issueBusinessUnit.issueInfo',
            'uploadProofDetails',
            'issueBusinessUnit.issueInfo.team_name']
            )
        ->get();
        // dd($issues);
        return view('for_approval_tracking',
        array(
            'header' => "Monitoring",
            'issues' => $issues,
        ));
    }
}
