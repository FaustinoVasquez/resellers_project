@extends('master')

@section('title','Ship Station Import')

@section('icon','fa-home')
@section('page')
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-body">
                  <div class="row">
                    @if(session('success'))
                      <div class="alert alert-success fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                        </button>
                        {{ session('success') }}
                      </div>
                    @endif
                  </div>
                  <div class="row">
                    <div class="col-sm-3">
                      <div id="infoData">
                          @include('partials/modal')
                          {!! Form::open(array('route' => 'shipImportStore','method'=>'post','id'=>'myForm', 'files'=>true)) !!}
                          <input type="hidden" name="_token" value="{{ csrf_token() }}">
                          <div class="form-group">
                              {!! Form::label('PO Number:',null,array('id' => 'poLabel')) !!}
                              {!! Form::text('PoNumber', null, array('id'=>'poNumber','class' => 'form-control')) !!}
                              <p class="errors">{!!$errors->first('PoNumber')!!}</p>
                          </div>
                          {!! Form::file('file') !!}
                          <p class="errors">{!!$errors->first('file')!!}</p>
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
