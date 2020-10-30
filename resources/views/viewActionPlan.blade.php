
<div class="modal fade" id="viewActionPlan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">View Action Plan</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <form method='post' action='' onsubmit='show();'  enctype="multipart/form-data" >
                <div class="modal-body">
                    @csrf
                    <input type='hidden' name='pendingIssueId' id='pendingIssueIdView' value=''>
                    <div class='action-plan-view'>   
                    </div>
                    
                </div>
                <div class='modal-body row'>
                    <div class='col-md-3'>
                        <a onclick='newViewPlanAction()'  class="btn btn-success" > + Add Action Plan</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type='submit'  class="btn btn-primary" >Submit</button>
                </div>
            </form>
        </div>
        <script>
            function newViewPlanAction()
            {
                var idactionview = $('.action-plan-view').children().last().attr('id');
                var idactionview = parseInt(idactionview) + 1;
                var result = "<div class='row mt-1' id='"+idactionview+"'><div class='col-md-8'>Action Plan :<textarea  class='form-control' style='resize: vertical;max-height: 2000px;' name='action_plan[]'  required></textarea></div><div class='col-md-3'>Target Date : <input type='date' min='{{date('Y-m-d')}}' name='target_date[]'  class='form-control' required></div><div class='col-md-1'><span class='form-control mt-4' ><a  onclick='removeactionplan("+idactionview+")' href='#' class='text-danger '>X</a></span></div>";
                $(".action-plan-view").append(result); 
            }
        </script>
    </div>
</div>
    
    
    