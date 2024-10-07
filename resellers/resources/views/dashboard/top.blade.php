
  <div class="col-sm-4">
    <div class="panel panel-default" id="panel-best-seller">
      <div class="panel-body">
        <div class="row">
          <div class="col-sm-12">
            <h5 class="subtitle">Best Sellers</h5>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            @foreach($bestSellers as $product)
              <span class="sublabel">{{$product['Name']}}</span>
              <div class="progress progress-sm">
                <div style="width:{{($product['total'] * 100)/$bestSellers[0]['total']}}%" aria-valuemax="{{$bestSellers[0]['total']}}" aria-valuemin="0" aria-valuenow="{{$product['total']}}" role="progressbar" class="progress-bar progress-bar-primary"></div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
