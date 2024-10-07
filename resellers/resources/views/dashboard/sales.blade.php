<div class="row">
  <div class="col-lg-4">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">All Sales</h3>
      </div>
      <div class="panel-body" id="salesAll">
        <form action="salesAll">
          <div class="row">
            <div class="col-sm-4">
              <label>From</label>
              <select name="start" class="form-control">
                @for($i = Carbon\Carbon::now()->year; $i > 2007; $i--)
                  <option value="{{$i}}">{{$i}}</option>
                @endfor
              </select>
            </div>
            <div class="col-sm-4">
              <label>To:</label>
              <select name="end" class="form-control">
                @for($i = Carbon\Carbon::now()->subYear()->year; $i > 2007; $i--)
                  <option value="{{$i}}">{{$i}}</option>
                @endfor
              </select>
            </div>
            <div class="col-sm-4">
              <label></label>
              <button type="submit" class="btn btn-primary btn-block" style="margin-top:3px">Filter</button>
            </div>
          </div>
        </form>
        <div id="table-salesAll" style="height:350px"></div>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Sales Paid</h3>
      </div>
      <div class="panel-body" id="salesPaid">
        <form action="salesPaid">
          <div class="row">
            <div class="col-sm-4">
              <label>From</label>
              <select name="start" class="form-control">
                @for($i = Carbon\Carbon::now()->year; $i > 2007; $i--)
                  <option value="{{$i}}">{{$i}}</option>
                @endfor
              </select>
            </div>
            <div class="col-sm-4">
              <label>To:</label>
              <select name="end" class="form-control">
                @for($i = Carbon\Carbon::now()->subYear()->year; $i > 2007; $i--)
                  <option value="{{$i}}">{{$i}}</option>
                @endfor
              </select>
            </div>
            <div class="col-sm-4">
              <label></label>
              <button type="submit" class="btn btn-primary btn-block" style="margin-top:3px">Filter</button>
            </div>
          </div>
        </form>
        <div id="table-salesPaid" style="height:350px"></div>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Sales pending payment</h3>
      </div>
      <div class="panel-body" id="salesPending">
        <form action="salesPaid">
          <div class="row">
            <div class="col-sm-4">
              <label>From</label>
              <select name="start" class="form-control">
                @for($i = Carbon\Carbon::now()->year; $i > 2007; $i--)
                  <option value="{{$i}}">{{$i}}</option>
                @endfor
              </select>
            </div>
            <div class="col-sm-4">
              <label>To:</label>
              <select name="end" class="form-control">
                @for($i = Carbon\Carbon::now()->subYear()->year; $i > 2007; $i--)
                  <option value="{{$i}}">{{$i}}</option>
                @endfor
              </select>
            </div>
            <div class="col-sm-4">
              <label></label>
              <button type="submit" class="btn btn-primary btn-block" style="margin-top:3px">Filter</button>
            </div>
          </div>
        </form>
        <div id="table-salesPending" style="height:350px"></div>
      </div>
    </div>
  </div>

</div>
