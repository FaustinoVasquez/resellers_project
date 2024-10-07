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
            <input type="hidden" name="omOrder" id="omOrder" />
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
