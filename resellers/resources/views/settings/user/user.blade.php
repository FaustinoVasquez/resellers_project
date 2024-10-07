@extends('master')

@section('title','Account Settings')

@section('css')
  <link href="{{ URL::asset('assets/select2/dist/css/select2.css') }}" rel="stylesheet"/>
  <link href="{{ URL::asset('assets/datatables/datatables.min.css') }}" rel="stylesheet"/>
  <link href="{{ URL::asset('css/settings/settings.css') }}" rel="stylesheet"/>
@endsection

@section('page')
  <div class="row">
      <div class="col-sm-">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-sm-12">
                <legend>Personal Info</legend>
              </div>
              <div class="col-sm-4">
                <form action="updateAccount">
                  <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" class="form-control" value="{{ Auth::user()->fullname }}" name="fullname"/>
                  </div>
                  <div class="form-group">
                    <label>Position</label>
                    <input type="text" class="form-control" value="{{ Auth::user()->Position }}" name="position"/>
                  </div>
                  <div class="form-group">
                    <label>Email</label>
                    <input type="text" class="form-control" value="{{ Auth::user()->email }}" name="email"/>
                  </div>
                  <div class="form-group">
                    <button class="btn btn-primary">Save Personal Info</button>
                  </div>
                </form>
              </div>
            </div>
            <br/>
            <div class="row">
              <div class="col-sm-12">
                <legend>Shipping Accounts</legend>
              </div>
              <div class="col-sm-4">
                <form action="updateShippingAccounts">
                  <div class="form-group">
                    <label>UPS Account</label>
                    <input type="text" class="form-control" value="{{ Auth::user()->CustomerUPSAccount }}" name="UPSAccount"/>
                  </div>
                  <div class="form-group">
                    <label>FedEx Account</label>
                    <input type="text" class="form-control" value="{{ Auth::user()->CustomerFEDEXAccount }}" name="FEDEXAccount"/>
                  </div>
                  <div class="form-group">
                    <label>DHL Account</label>
                    <input type="text" class="form-control" value="{{ Auth::user()->CustomerDHLAccount }}" name="DHLAccount"/>
                  </div>
                  <div class="form-group">
                    <button class="btn btn-primary">Save Shipping Accounts</button>
                  </div>
                </form>
              </div>
            </div>
            <br/>
            <div class="row">
              <div class="col-sm-12">
                <legend>Password Change</legend>
              </div>
              <div class="col-sm-4">
                <form action="updatePassword">
                  <div class="form-group">
                    <label>Old Password</label>
                    <input type="password" class="form-control" name="OldPassword"/>
                  </div>
                  <div class="form-group">
                    <label>New Password</label>
                    <input type="password" class="form-control" name="NewPassword"/>
                  </div>
                  <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" class="form-control" name="ConfirmPassword"/>
                  </div>
                  <div class="form-group">
                    <button class="btn btn-primary">Save New Password</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>
@endsection

@section('scripts')
  <script src="{{ URL::asset('js/settings/settings.js') }}"></script>
@endsection
