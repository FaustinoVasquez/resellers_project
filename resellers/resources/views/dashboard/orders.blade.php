
  <div class="col-sm-8">
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="row">
          <div class="col-sm-12">
            <h5 class="subtitle">Order Quicksearch</h5>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <input type="text" placeholder="Order # or Ship name" class="form-control" id="quicksearch-input"/>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="table-responsive">
              <table class="table table-condensed table-striped table-bordered text-center" id="quicksearch">
                <thead class="text-center">
                  <th class="text-center">Order#</th>
                  <th class="text-center">Reference#</th>
                  <th class="text-center">Traking Number</th>
                  <th class="text-center">Order Status</th>
                  <th class="text-center">PO Number</th>
                  <th class="text-center">Ship Name</th>
                  <th class="text-center">Order Date</th>
                  <th class="text-center">Grand Total</th>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-sm-12">
            <a class="btn btn-link pull-right" href="{{ URL::to('omc') }}">View all orders</a>
          </div>
        </div>
      </div>
    </div>
  </div>
