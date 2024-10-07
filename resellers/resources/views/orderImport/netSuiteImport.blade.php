@extends('master')

@section('title','Net Suite Import')

@section('icon','fa-home')
@section('page')
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div id="infoData">
                      <div class="row">
                        @if(session('success'))
                          <div class="alert alert-success fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                             <span aria-hidden="true">&times;</span>
                            </button>
                            {{ session('success') }}
                          </div>
                        @endif
                        <div class="col-sm-3">
                          @include('partials/modal')
                          {!! Form::open(array('route' => 'NetImportStore','method'=>'post','id'=>'myForm', 'files'=>true)) !!}
                          <input type="hidden" name="_token" value="{{ csrf_token() }}">
                          <div class="form-group">
                              {!! Form::label('PO Number:') !!}
                              {!! Form::text('PoNumber', null, array('id'=>'poNumber','class' => 'form-control')) !!}
                          </div>
                          <div class="form-group">
                            {!! Form::file('file') !!}
                          </div>
                          <div class="form-group">
                              <button type="submit" class="btn btn-primary" id="submit-button">Process File</button>
                          </div>
                          {!! Form::close() !!}
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ URL::asset('js/shipStation/shipStation.js') }}"></script>
  @if(session('error'))
    <script>
      gritter('Error','{!! session("error") !!}',"danger");
    </script>
  @endif
@endsection
