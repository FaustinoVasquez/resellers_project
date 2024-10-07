@extends('master')

@section('title','Shipping Info')
@section('buttons','<button class="btn btn-sm btn-primary pull-right flux-button visible-xs visible-sm btn-process"><i class="fa fa-check visible-xs-block" style="border:none;padding:0;margin: 0 10px;" aria-hidden="true"></i><span class="hidden-xs">Finish Order</span></button> <a class="btn btn-sm btn-warning pull-right flux-button visible-xs visible-sm btn-rmvOrder" href="'. URL::to("checkout") .'"><i class="fa fa-arrow-left visible-xs-block" style="border:none;padding:0;margin: 0 10px;" aria-hidden="true"></i><span class="hidden-xs">Shipping Method</span></a>')

@section('css')
  <link href="{{ URL::asset('css/checkout/checkout.css') }}" rel="stylesheet"/>
@endsection

@section('page')

    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">

              <div class="panel-heading">
                <h3 class="panel-title">
                  <div class="flux-buttons hidden-xs hidden-sm">
                    <button class="btn btn-primary pull-right flux-button btn-process">Finish Order</button>
                    <a class="btn btn-warning pull-right flux-button btn-rmvOrder" href="{{ URL::to('checkout') }}">Shipping Method</a>
                  </div>
                  Order Id: {{ session('webOrderID') }}
                </h3>
              </div>

              <div class="panel-body">
                <form action="{{ URL::to('sendOrder') }}" method="post" id="form-shipping-info">
                  {{ csrf_field() }}
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="panel panel-primary">
                        <div class="panel-heading">
                          <h3 class="panel-title">Shipping From <button type="button" class="btn btn-success pull-right" 
                                                                        style="margin-top:-11px;margin-left: 10px" 
                                                                        id="btn-copyShipFromToShipTo" 
                                                                        data-toggle="button" 
                                                                        aria-pressed="false" autocomplete="off">ShipFrom to ShipTo</button>
                                                                <button type="button" class="btn btn-primary pull-right" 
                                                                        style="margin-top:-11px;margin-left: 10px" 
                                                                        id="btn-blind" 
                                                                        data-toggle="button" 
                                                                        aria-pressed="false" autocomplete="off">Blind</button>

                           </h3>
                        </div>
                        <div class="panel-body">
                          <div class="row">
                            <div class="col-sm-12">
                              <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" id="fromFullName" name="fromFullName" class="form-control blind-store" value="{{ old('fromFullName') ? old('fromFullName') : $order->ShipFromName}}"/>
                              </div>
                              <div class="form-group">
                                <label>Company</label>
                                <input type="text" id="fromCompany" name="fromCompany" class="form-control blind-store" value="{{ old('fromCompany') ? old('fromCompany') : $order->ShipFromCompany }}"/>
                              </div>
                              <div class="form-group">
                                <label>Address 1</label>
                                <input type="text" id="fromAddress" name="fromAddress" class="form-control blind-store" value="{{ old('fromAddress') ? old('fromAddress') :  $order->ShipFromAddressLine1 }}"/>
                              </div>
                              <div class="form-group">
                                <label>Address 2</label>
                                <input type="text" id="fromAddress2" name="fromAddress2" class="form-control blind-store" value="{{ old('fromAddress2') ? old('fromAddress2') :  $order->ShipFromAddressLine2 }}"/>
                              </div>
                              <div class="row">
                                <div class="col-sm-4 form-group">
                                  <label>City</label>
                                  <input type="text" id="fromCity" name="fromCity" class="form-control blind-store" value="{{ old('fromCity') ? old('fromCity') :  $order->ShipFromCity }}"/>
                                </div>
                                <div class="col-sm-4 form-group">
                                  <label>State</label>
                                  <input type="text" id="fromState" name="fromState" class="form-control blind-store" value="{{ old('fromState') ? old('fromState') :  $order->ShipFromState }}"/>
                                </div>
                                <div class="col-sm-4 form-group">
                                  <label>Zip Code</label>
                                  <input type="text" id="fromZip" name="fromZip" class="form-control blind-store" value="{{ old('fromZip') ? old('fromZip') :  $order->ShipFromZipCode }}"/>
                                </div>
                              </div>
                              <div class="form-group">
                                <label>Country</label>
                                <select id="fromCountry" name="fromCountry" class="form-control blind-store">
					{!!$countriesSelect!!}
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Shipping to -->
                    <div class="col-lg-6">
                      <div class="panel panel-primary">
                        <div class="panel-heading">
                          <h3 class="panel-title">Shipping to</h3>
                        </div>
                        <div class="panel-body">
                          <div class="row">
                            <div class="col-sm-12">
                              <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" id="toFullName" name="toFullName" class="form-control" value="{{ old('toFullName') }}"/>
                              </div>
                              <div class="form-group">
                                <label>Company</label>
                                <input type="text" id="toCompany" name="toCompany" class="form-control" value="{{ old('toCompany')  }}"/>
                              </div>
                              <div class="row">
                                <div class="col-sm-12 form-group">
                                  <label>Address 1</label>
                                  <input type="text" id="toAddress" name="toAddress" class="form-control" value="{{ old('toAddress')  }}"/>
                                </div>
                                <div class="col-sm-6 form-group">
                                  <label>Address 2</label>
                                  <input type="text" id="toAddress2" name="toAddress2" class="form-control" value="{{ old('toAddress2')  }}"/>
                                </div>
                                <div class="col-sm-6 form-group">
                                  <label>Email</label>
                                  <input type="text" id="toEmail" name="toEmail" class="form-control" value="{{ old('toEmail')  }}"/>
                                </div>
                                <div class="col-sm-4 form-group">
                                  <label>City</label>
                                  <input type="text" id="toCity" name="toCity" class="form-control" value="{{ old('toCity')  }}"/>
                                </div>
                                <div class="col-sm-4 form-group">
                                  <label>State</label>
                                  <input type="text" id="toState" name="toState" class="form-control" value="{{ old('toState')  }}"/>
                                </div>
                                <div class="col-sm-4 form-group">
                                  <label>Zip Code</label>
                                  <input type="text" id="toZip" name="toZip" class="form-control" value="{{ old('toZip')  }}"/>
                                </div>
                              </div>
                              <div class="form-group">
                                <label>Country</label>
                                <select id="toCountry" name="toCountry" class="form-control">
					{!!$countriesSelect!!}
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script src="{{ URL::asset('js/checkout/shippingInformation.js') }}"></script>
<script>
$(function(){

   $("#btn-copyShipFromToShipTo").on('click',function(){
	$("#toFullName").val($("#fromFullName").val());
        $("#toCompany").val($("#fromCompany").val());
	$("#toAddress").val($("#fromAddress").val());
	$("#toAddress2").val($("#fromAddress2").val());
	$("#toCity").val($("#fromCity").val());
	$("#toState").val($("#fromState").val());
	$("#toZip").val($("#fromZip").val());
	$("#toCountry").val($("#fromCountry").children("option:selected").val());
    });
});

</script>
  @if(session('errorMessage'))
    <script>
      gritter("Error", "{!! session('errorMessage') !!}" ,"danger")
    </script>
  @endif
@endsection
