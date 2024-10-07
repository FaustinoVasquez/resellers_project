<!-- Modal -->
<div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Special Request</h4>
      </div>
      <div class="modal-body">
        {!! Form::open(array('route' => 'sendSpecialRequest','method'=>'get','id'=>'bcForm','class'=>'form')) !!}

        <div class="form-group">
          {!!  Form::label('email', 'To:', array('class' => 'awesome'))!!}
          <input type="email" value="specialrequests@mitechnologiesinc.com" class="form-control" disabled="true"/>
        </div>
        <div class="form-group">
          <label>Message</label>
          <textarea name="notes" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <input name="from" type="hidden" value="{{auth()->user()->email}}"/>
        </div>
        <div class="form-group ">
            <table id="table-requested" class="table">
              <thead>
                <th>SKU</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Remove</th>
              </thead>
              <tbody>
              </tbody>
            </table>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary btn-block visible-xs visible-sm btn-bottom-margin-10">Send</button>
        {!! Form::submit('Send Special Request', array('class' => 'btn btn-primary btn-fill hidden-xs hidden-sm')) !!}
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
