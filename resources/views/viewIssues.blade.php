<div class="modal fade" id="viewIssues{{$code->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                <h5 class="modal-title" id="exampleModalLabel">{{$code->bu_name}}</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            
           
                <div class="modal-body">
                    <div class='row border'>
                        <div class='col-md-4'>
                            Action Owner
                        </div>
                        <div class='col-md-4'>
                            Engagement Title
                        </div>
                        <div class='col-md-4'>
                            Issue
                        </div>
                    </div>
                    @foreach($code->issues_info as $issue)
                    <div class='row border'>
                        <div class='col-md-4'>
                            {{$issue->ownerInfo->name}}
                        </div>
                        <div class='col-md-4'>
                            {{$issue->issueInfo->engagement_title}}
                        </div>
                        <div class='col-md-4'>
                            {{$issue->issueInfo->issue}}
                        </div>
                    </div>
                   @endforeach
                </div>
        </div>
    </div>
</div>
