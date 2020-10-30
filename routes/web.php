<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();
Route::group( ['middleware' => 'auth'], function()
{
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/', 'HomeController@index')->name('home');

    //users function



    //issue
   
    Route::get('get-process-owner','IssueController@viewProcessOwner');
    Route::post('new-issue','IssueController@newIssue');
    Route::get('pending-issue','IssueController@pendingIssue');
    Route::get('/remove-issue/{issueId}','IssueController@removeIssue');
    Route::get('/get-issue','IssueController@getIssue');
    Route::post('save-issue','IssueController@saveEditIssue');


    // Route::post('')
    //Action
    Route::post('/submit-action-plan/{issueId}','ActionController@newAction');
    Route::post('/edit-action-plan/{actionId}','ActionController@saveEditAction');
    Route::post('pending-issue','ActionController@saveActionPlan');
    Route::get('/get-action-plan','ActionController@getActionPlan');
    Route::get('/cancel-action-plan','ActionController@cancelActionPlan');
    Route::get('/approve-action-plan','ActionController@approveActionPlan');
    Route::post('/approve-request/{actionID}','ActionController@approveActionPlans');
    Route::post('/cancel-request/{actionID}','ActionController@cancelRequest');
    Route::get('/action-plans','ActionController@allActionPlans');
    Route::get('/action-plans-due','ActionController@actionPlanDue');
    //upload proof
    Route::post('upload-proof/{actionId}','ActionController@uploadProof');
    Route::post('update-proof','ActionController@updateProof');
    Route::get('approve_action_plan/{IssueId}','ActionController@approvedActionPlan');
    Route::get('view-proof-plan','ActionController@viewProofPlan');
    Route::get('monitoring','ActionController@monitoring');
    //attachment
    Route::get('/remove-attachment','ActionController@removeAttachment');
    
    //For Approval
    Route::get('/for-approval','IssueController@forApproval');
    Route::get('/get-action-plan-for-approval','IssueController@getAllForApproval');

    //For Audit
    Route::get('/for-audit','IssueController@forAudit');
    Route::post('verify-action-plan','IssueController@verifyActionPlan');
    Route::post('return-action-plan','IssueController@returnActionPlan');
    Route::get('verified-action-plans','IssueController@verifyActionPlans');
    Route::get('return-action-plans','IssueController@returnActionPlans');
    Route::post('closed/{issueId}','IssueController@issueClosed');
    Route::get('closed-issues','IssueController@ClosedIssues');

    Route::post('share/{issueId}','IssueController@shareIssues');
    Route::get('potential-issues','IssueController@potentialIssues');
    Route::group(['middleware' => 'foraudit'], function () {
        Route::get('issue','IssueController@viewIssues');
        Route::get('/manage-users','ManageUserController@viewManageAccount');
        Route::get('/manage-account-edit/{account_id}','ManageUserController@editManageAccount');
        Route::post('/manage-account-edit/new-manage-account/{userId}','ManageUserController@newManageUser');
        Route::get('manage-account-edit/remove-user/{manageUserId}','ManageUserController@removeManageUser');
        //auditors
        Route::get('/auditors','AuditorController@auditorView');
        Route::get('/users','AccountController@viewUsers');
        Route::post('/change-password','AccountController@changePassword');
        Route::post('/add-account','AccountController@newAccount');
        Route::post('/edit-account/{id}','AccountController@editAccount');
        Route::get('/reset-account/{id}','AccountController@resetPassword');
        Route::get('/remove-account/{id}','AccountController@removeAccount');
            //manage account
        Route::get('/manage-users','ManageUserController@viewManageAccount');
        Route::get('/manage-account-edit/{account_id}','ManageUserController@editManageAccount');
        Route::post('/manage-account-edit/new-manage-account/{userId}','ManageUserController@newManageUser');
        Route::get('manage-account-edit/remove-user/{manageUserId}','ManageUserController@removeManageUser');
        //auditors
        Route::get('/auditors','AuditorController@auditorView');
        Route::post('edit-account-auditor/{accountId}','AuditorController@saveEditAccount');

        //business unit
        Route::get('business-unit','BusinessUnitController@viewBusinessUnit');
        Route::post('add-business','BusinessUnitController@newBusinessUnit');
        Route::post('edit-business/{codeId}','BusinessUnitController@editBusinessUnit');
        
        Route::get('manual-email','HomeController@emailManual');
        Route::get('summary-issues','IssueController@viewSummaryIssues');
        Route::get('get-cluster-dashboard','IssueController@viewIssuesCluster');
        Route::get('get-open-close','IssueController@viewOpenClose');
    });
}
);

