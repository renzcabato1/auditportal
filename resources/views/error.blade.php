@if (count($errors))
    <div class="alert alert-danger alert-dismissible fade show " role="alert">
    @foreach($errors->all() as $error)
        <span class="alert-inner--icon"><i class="ni n
            .i-fat-remove"></i></span>
        <span class="alert-inner--text"><strong>Error!</strong> {{ $error }}</span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    @endforeach
    </div>
@endif