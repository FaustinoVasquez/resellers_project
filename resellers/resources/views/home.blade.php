@extends('layout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @include('partials/success')
                <div class="panel panel-default">
                    <div class="panel-heading">Welcome!</div>
                    <div class="panel-body">
                        <p>Welcome to resellers</p>
                        {{$passw}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
