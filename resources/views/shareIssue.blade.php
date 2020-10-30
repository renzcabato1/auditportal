<div class="modal fade" id="share{{$issue->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">Share With</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <style>
                
                #bucodes{{$issue->id}}_chosen{
                    width: 100% !important;
                }
            </style>
            <form  method='POST' action='share/{{$issue->id}}' onsubmit="show()" enctype="multipart/form-data" >
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class='business-share'>
                        <div class='row' >
                            <div class='col-md-12'>
                                Bussiness Unit :
                               
                                <select class='form-control chosen-select' id='bucodes{{$issue->id}}' name='bu_code[]' multiple >
                                    <option value=''></option>
                                    @foreach($bu_codes as $bu_code)
                                    <option value='{{$bu_code->id}}' {{ (in_array($bu_code->id, ($issue->potentialIssues->pluck('id'))->toArray()) ? "selected":"") }}>{{$bu_code->bu_name}} - {{$bu_code->bu_code}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit"  class="btn btn-primary" >Submit</button>
                </div>
            </div>
        </form>
        
    </div>
</div>
