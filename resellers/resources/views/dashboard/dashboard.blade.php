@extends('master')

@section('title','Dashboard')

@section('css')
  <link href="{{ URL::asset('css/dashboard/dashboard.css') }}" rel="stylesheet"/>
  <link href="{{ URL::asset('assets/datatables/datatables.min.css') }}" rel="stylesheet"/>
@endsection

@section('page')
<!-- Modal -->
<div class="modal fade" id="modal-tracking-number" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Tracking Number</h4>
      </div>
      <div class="modal-body" id="body-tracking-number">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
          <div class="panel-body">
            @include('partials/details')
            <div id="infoData">
              @include('dashboard.badges')
              <div class="row">
                @include('dashboard.orders')
                @include('dashboard.top')
              </div>
              @include('dashboard.sales')
            </div>
          </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script src="{{ URL::asset('js/flot/jquery.flot.time.min.js') }}"></script>
<script src="{{ URL::asset('js/dashboard/dashboard.js') }}"></script>
<script src="{{ URL::asset('assets/datatables/datatables.js') }}"></script>
@if(session('successMessage'))
  <script>
    gritter("Order Completed", "{!! session('successMessage') !!}" ,"success");
  </script>
@endif

@endsection
