<!-- Modal -->
<div id="modal-sku" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Custom SKU</h4>
            </div>
            <div class="modal-body">
              <form action="skuMapEdit" method="POST" id="form-map-sku">
                <div class="row">
                  <div class="col-sm-6 form-group">
                    <input type="text" class="form-control" name="CustomerSKU" placeholder="Customer SKU"/>
                  </div>
                  <div class="col-sm-6 form-group">
                    <input type="text" class="form-control" name="MITSKU" placeholder="MITSKU" readonly="true"/>
                  </div>
                  <input type="hidden" name="oper"/>
                  <input type="hidden" name="description" value=""/>
                  <input type="hidden" name="id"/>
                  <div class="col-sm-6 form-group">
                    <input type="text" class="form-control" id="search" placeholder="Search Product"/>
                  </div>
                  <div class="col-sm-6 form-group">
                    <button id="btn-bind-sku" class="btn btn-primary btn-block">Map SKU</button>
                  </div>
                </form>
                <div class="col-sm-12 table-responsive">
                  <table class="table table-condensed table-hover" id="table-mapping" width="100%">
                    <thead>
                      <th>Product SKU</th>
                      <th>Product Description</th>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
