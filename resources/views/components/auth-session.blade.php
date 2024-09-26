<?php
use Illuminate\Support\Facades\Session;

?>

@if(Session::has('status.message'))
    <div class="alert alert-{{ Session::get('status.type') }} my-3">
        {!! Session::get('status.message') !!}
    </div>
@endif
