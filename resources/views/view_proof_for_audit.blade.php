<div class="modal fade" id="viewproof" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">View Proof</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            
            <form  method='POST' action='update-proof' onsubmit="show()" enctype="multipart/form-data" >
                <div class="modal-body">
                    {{ csrf_field() }}
                    <input type='hidden'  name='proofId' id='proofIdData'>
                  
                    <div class='row'>
                        <div class='col-md-12'>
                            Remarks
                            <textarea name="remarks" id='remarksEdit' class='form-control' ></textarea>
                        </div>
                    </div>
                    <br>
                    <h4>Attachments</h4>
                    <div class='attachments'>
                    </div>
                
                </div>
            </form>
        </div>
    </div>
</div>
