<div class="modal fade bs-example-modal-panel" id="modal-delete-order" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content"><div class="panel panel-danger panel-alt">
    <div class="panel-body text-center">
      <div class="row">
        <i class="fa fa-exclamation" style="font-size:60px"></i>
      </div>
      <div class="row form-group">
        <h3 style="margin-top: 10px;margin-bottom: 20px;">Are you sure you want to delete this order?</h3>
      </div>
      <div class="row">
        <div class="btn-group" role="group" aria-label="...">
          <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger btn-lg" id="btn-del-confirm">Confirm</button>
        </div>
      </div>
    </div>
</div></div>
  </div>
</div>

<div class="modal fade" id="modal-attach-label" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title" id="myModalLabel">Attach a shipping label</h4>
        </div>
        <div class="modal-body">
          <form action="{{ URL::to('uploadShippingLabel') }}" method="post" class="dropzone padded" enctype="multipart/form-data" id="formLabel">
            {{ csrf_field() }}
            <div class="dz-message" data-dz-message><span>Click or drag files here (.jpg,.jpeg and .pdf)</span></div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" id="upload-labels" class="btn btn-primary">Save Shipping label</button>
        </div>
    </div><!-- modal-content -->
  </div><!-- modal-dialog -->
</div>
