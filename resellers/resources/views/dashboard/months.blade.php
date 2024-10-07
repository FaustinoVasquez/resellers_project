
  <div class="col-sm-4">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Last 5 Orders</h3>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-sm-12">
            <table class="table table-condensed table-striped table-bordered text-center">
              <thead class="text-center">
                <th class="text-center">Month</th>
                <th class="text-center">Total</th>
              </thead>
              @for($i = 0; $i < 5; $i++)
                <tr>
                  <td>{{Carbon\Carbon::now()->subMonths($i)->format("M")}}</td>
                  <td>15</td>
                </tr>
              @endfor
            </table>
          </div>
          <div class="col-sm-12">
            <a class="btn btn-link pull-right" href="{{ URL::to('omc') }}">View all orders</a>
          </div>
        </div>
      </div>
    </div>
  </div>
