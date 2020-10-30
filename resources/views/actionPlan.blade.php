
<div class="modal fade" id="actionPlan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <form method='post' action="pending-issue" onsubmit='show();' >
                <div class="modal-header">
                    <div class='col-md-10'>
                        <h5 class="modal-title" id="exampleModalLabel">Action Plan</h5>
                    </div>
                    <div class='col-md-2'>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                
                <div class="modal-body ">
                    <input type='hidden' name='pendingIssueId' id='pendingIssueId' value=''>
                    
                    <div class='row' id='1'>
                        <div class='col-md-8'>
                            Action Plan :
                            <textarea  class='form-control' style='resize: vertical;max-height: 2000px;' name='action_plan[]'  required></textarea>
                        </div>
                        <div class='col-md-4'>
                            Target Date :
                            <input type='date' min='{{date('Y-m-d')}}' name='target_date[]'  class='form-control' required>
                        </div>
                    </div >
                    <div class='action-plan'>
                    </div>
                </div>
                <div class='modal-body row'>
                    <div class='col-md-3'>
                        <a onclick='newActionPlan()'  class="btn btn-success" > + Add Action Plan</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type='submit'  class="btn btn-primary" >Submit</button>
                </div>
            </form>
        </div>
        <script>
            function newActionPlan()
            {
                var idPendingIssue = $('.action-plan').children().last().attr('id');
                var idPendingIssue = parseInt(idPendingIssue) + 1;
                if(isNaN(idPendingIssue)){
                    var idPendingIssue = parseInt(2);
                }
                var result = "<div class='row mt-1' id='"+idPendingIssue+"'><div class='col-md-8'>Action Plan :<textarea  class='form-control' style='resize: vertical;max-height: 2000px;' name='action_plan[]'  required></textarea></div><div class='col-md-3'>Target Date : <input type='date' min='{{date('Y-m-d')}}' name='target_date[]'  class='form-control' required></div><div class='col-md-1'><span class='form-control mt-4' ><a  onclick='removeactionplan("+idPendingIssue+")' href='#' class='text-danger '>X</a></span></div>";
                $(".action-plan").append(result);  
            }
            function removeactionplan(id)
            {
                $("#"+id).remove();
            }
        </script>
    </div>
</div>


