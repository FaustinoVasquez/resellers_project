@extends('master')

@section('title','Checkout')
@section('buttons','<button class="btn btn-sm btn-primary pull-right flux-button visible-xs visible-sm visible-sm btn-process"><i class="fa fa-arrow-right visible-xs-block" style="border:none;padding:0;margin: 0 10px;" aria-hidden="true"></i> <span class="hidden-xs">Shipping Information</span></button> <button class="btn btn-sm btn-warning pull-right flux-button visible-xs visible-sm btn-rmvOrder"><i class="fa fa-trash visible-xs-block" style="border:none;padding:0;margin: 0 10px;" aria-hidden="true"></i><span class="hidden-xs">Cancel Order</buton></button>')

@section('css')
<link href="{{ URL::asset('css/checkout/checkout.css') }}" rel="stylesheet"/>
<link href="{{ URL::asset('assets/fileupload/css/jquery.fileupload-ui.css') }}" rel="stylesheet"/>
@endsection

@section('page')
    @include('partials.confirm')
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                  <h3 class="panel-title">
                    <div class="flux-buttons hidden-xs hidden-sm">
                      <button class="btn btn-primary pull-right flux-button btn-process">Shipping Information</button>
                      <button class="btn btn-warning pull-right flux-button btn-rmvOrder">Cancel Order</button>
                    </div>
                    Order Id: {{ session('webOrderID') }}
                  </h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                      <div class="col-lg-6 col-lg-push-6 col-md-12">
                        <div class="panel panel-primary">
                          <div class="panel-heading">
                            <h3 class="panel-title">Shipping Method Info</h3>
                          </div>
                          <div class="panel-body">
                            <div class="row">
                              <div class="col-sm-12">
                                <form action="{{ URL::to('processOrder') }}" method="post" id="form-order">
                                  {{ csrf_field() }}
                                  <div class="form-group">
                                    <label>Shipping Method</label>
                                    <select name="ShippingMethod" class="form-control" id="shipping-method">
                                      <option value="Standard (5-7 Business Days)" {{($order->ShippingMethod == 'Standard (5-7 Business Days)') ? 'selected' : ''}}>Standard (5-7 Business Days)</option>
                                      <option value="Two-Day (No Saturday Delivery)" {{($order->ShippingMethod == 'Two-Day (No Saturday Delivery)') ? 'selected' : ''}}>Two-Day (No Saturday Delivery)</option>
                                      <option value="One-Day (No Saturday Delivery)" {{($order->ShippingMethod == 'One-Day (No Saturday Delivery)') ? 'selected' : ''}}>One-Day (No Saturday Delivery)</option>
                                      <option value="Shipping Label Attached" {{($order->ShippingMethod == 'Shipping Label Attached') ? 'selected' : ''}}>Shipping Label Attached</option>
				      <option value="Ground" {{($order->ShippingMethod == 'Ground') ? 'selected' : ''}}>Ground</option>
				      <option value="International" {{($order->ShippingMethod == 'International') ? 'selected' : ''}}>International</option>
                                    </select>
                                  </div>
                                  <div class="form-group">
                                    <label>PO#</label>
                                    <input type="text" class="form-control" name="poNumber" value="{{ old('poNumber') ? old('poNumber') : $order->PONumber }}"/>
                                  </div>
                                  <div class="form-group">
                                    <label>Notes to be printed on Package Slip</label>
                                    <textarea class="form-control" name="notes" >{{ old('notes') ? old('notes') : $order->PackingSlipNotes }}</textarea>
                                  </div>
                                  <div class="form-group">
                                    <label>Special notes to Shipping Department</label>
                                    <textarea class="form-control" name="specialNotes" >{{ old('specialNotes') ? old('specialNotes') : $order->InternalNotes }}</textarea>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6 col-lg-pull-6 col-md-12">
                        <div class="panel panel-primary">
                          <div class="panel-heading">
                            <h3 class="panel-title">Items in cart</h3>
                          </div>
                          <div class="panel-body inside-panel" id="table-holder">
                            @include('checkout.current')
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
  <script src="{{ URL::asset('assets/dropzone/dropzone.js') }}"></script>
  <script src="{{ URL::asset('js/checkout/checkout.js') }}"></script>
  @if(session('errorMessage'))
    <script>
      gritter("Error", "{!! session('errorMessage') !!}" ,"danger")
    </script>
  @endif
@endsection
