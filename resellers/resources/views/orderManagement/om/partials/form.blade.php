<div class="row">
      {!! Form::open(array('route' => 'omSearch', 'id'=>'myForm','class'=>' ajax')) !!}
          <div class="form-group col-sm-2">
            <div class="input-group">
                 <span class="input-group-addon">
                    <i class="fa fa-search"></i>
                </span>
                {!! Form::text('Search', null, [ 'id'=>'search','class' => 'form-control','placeholder'=>'Search' ]) !!}
            </div>
          </div>

          <div class="form-group col-sm-2">
            <div class="input-group">
                 <span class="input-group-addon">
                     <i class="fa fa-check-square-o"></i>
                 </span>
            {!! Form::select('Status', $status, null, ['id'=>'status','class' => 'form-control']) !!}
            </div>
          </div>

          <div class="form-group col-sm-2">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </span>
            {!! Form::text('DateFrom', $from, ['id' =>'datefrom','class' => 'datePicker form-control' ]) !!}
            </div>
          </div>

          <div class="form-group col-sm-2">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </span>
            {!! Form::text('DateTo', $to, ['id' =>'dateto','class' => 'datePicker form-control']) !!}
            </div>
          </div>
          
          <div class="form-group col-sm-2">
            <button class="btn btn-primary btn-block visible-xs visible-sm btn-bottom-margin-10">Submit</button>
            {!! Form::submit('Submit', array('class' => 'btn btn-primary btn-fill hidden-xs hidden-sm')) !!}
          </div>
      {!! Form::close() !!}
</div>
