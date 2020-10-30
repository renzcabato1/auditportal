<?php

namespace App\Http\Controllers;
use App\Code;
use App\Cluster;
use App\Account;
use App\BuManager;
use Illuminate\Http\Request;

class BusinessUnitController extends Controller
{
    //
    public function viewBusinessUnit()
    {
        $codes = Code::with('cluster_info','bu_head_info','cluster_head_info','managers_data','managers_data.employee_info')
        ->orderBy('bu_name','asc')
        ->get();
        // dd($codes);
        $clusters = Cluster::orderBy('cluster_name','asc')->get();
        $cluster_heads = Account::with('employee_info')
        ->where('role','like','%2%')
        ->get();
        $bu_heads = Account::with('employee_info')
        ->where('role','like','%3%')
        ->get();
        $managers = Account::with('employee_info')
        
        ->where('role','like','%4%')
        ->get();
        return view('view_business_units',
        array(
            'header' => "Business Unit",
            'codes' =>  $codes,
            'clusters' =>  $clusters,
            'cluster_heads' =>  $cluster_heads,
            'bu_heads' =>  $bu_heads,
            'managers' =>  $managers,
        ));
    }
    public function newBusinessUnit(Request $request)
    {
        
        $request->validate(['bu_code' => 'required|unique:codes,bu_code|max:255']); 
        $code = new Code;
        $code->cluster_id = $request->cluster;
        $code->bu_name = $request->bu_name;
        $code->bu_code = $request->bu_code;
        $code->cluster_head = $request->cluster_head;
        $code->bu_head = $request->bu_head;
        $code->save();
        if($request->managers != null)
        {
            foreach($request->managers as $manager)
            {
                $bu_manager = new BuManager;
                $bu_manager->bu_id = $code->id;
                $bu_manager->manager_id = $manager;
                $bu_manager->save();
            }
        }
        $request->session()->flash('status','Successfully added');
        return back();
    }
    public function editBusinessUnit(Request $request,$codeId)
    {
        // dd($request->all());
        $request->validate(['bu_code' => 'required|unique:codes,bu_code,'.$codeId.'|max:255']); 
        $code = Code::findOrfail($codeId);
        $code->cluster_id = $request->cluster;
        $code->bu_name = $request->bu_name;
        $code->bu_code = $request->bu_code;
        $code->cluster_head = $request->cluster_head;
        $code->bu_head = $request->bu_head;
        $code->save();
        $remove_manager = BuManager::where('bu_id','=',$code->id)->delete();
        if($request->managers != null)
        {
            foreach($request->managers as $manager)
            {
                $bu_manager = new BuManager;
                $bu_manager->bu_id = $code->id;
                $bu_manager->manager_id = $manager;
                $bu_manager->save();
            }
        }

        $request->session()->flash('status','Successfully Updated');
        return back();

    }
   
}
