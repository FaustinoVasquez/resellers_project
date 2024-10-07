<div class="row">

        <div class="col-sm-6 col-md-3">
          <div class="panel panel-success panel-stat">
            <div class="panel-heading">

              <div class="stat">
                <div class="row">
                  <div class="col-xs-4">
                    <i class="fa fa-shopping-cart fa-5x"></i>
                  </div>
                  <div class="col-xs-8 pd-top-5">
                    <small class="stat-label">Today Orders</small>
                    <h1>{{$todayTotal}}</h1>
                  </div>
                </div><!-- row -->
              </div><!-- stat -->

            </div><!-- panel-heading -->
          </div><!-- panel -->
        </div><!-- col-sm-6 -->

        <div class="col-sm-6 col-md-3">
          <div class="panel panel-primary panel-stat">
            <div class="panel-heading">

              <div class="stat">
                <div class="row">
                  <div class="col-xs-4">
                    <i class="fa fa-money"></i>
                  </div>
                  <div class="col-xs-8 pd-top-5">
                    <small class="stat-label">Today Total Rebates</small>
                    <h1>${{$todayRebates}}</h1>
                  </div>
                </div><!-- row -->

              </div><!-- stat -->

            </div><!-- panel-heading -->
          </div><!-- panel -->
        </div><!-- col-sm-6 -->

        <div class="col-sm-6 col-md-3">
          <div class="panel panel-danger panel-stat">
            <div class="panel-heading">

              <div class="stat">
                <div class="row">
                  <div class="col-xs-4">
                    <i class="fa fa-credit-card"></i>
                  </div>
                  <div class="col-xs-8">
                    <small class="stat-label pd-top-5">Today Total Due</small>
                    <h1>${{ $todayDue }}</h1>
                  </div>
                </div><!-- row -->

              </div><!-- stat -->

            </div><!-- panel-heading -->
          </div><!-- panel -->
        </div><!-- col-sm-6 -->

        <div class="col-sm-6 col-md-3">
          <div class="panel panel-dark panel-stat">
            <div class="panel-heading">

              <div class="stat">
                <div class="row">
                  <div class="col-xs-4">
                    <i class="fa fa-database"></i>
                  </div>
                  <div class="col-xs-8 pd-top-5">
                    <small class="stat-label">Today Total Products</small>
                    <h1>{{ $todayProducts }}</h1>
                  </div>
                </div><!-- row -->

              </div><!-- stat -->

            </div><!-- panel-heading -->
          </div><!-- panel -->
        </div><!-- col-sm-6 -->
      </div>
