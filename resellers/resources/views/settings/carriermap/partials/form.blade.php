<div class="row">

          {!! Form::open(array('route' => 'carrierMapSearch','method'=>'get','id'=>'myForm','class'=>'ajax')) !!}

          <div class="form-group col-sm-2">
            <div class="input-group">
                 <span class="input-group-addon">
                    <i class="fa fa-search"></i>
                </span>
                {!! Form::text('Search', null, ['id'=>'search','class' => 'form-control','placeholder'=>'Search' ]) !!}
            </div>
          </div>

          <div class="form-group col-sm-2">
            <button class="btn btn-primary btn-block visible-xs visible-sm btn-bottom-margin-10">Submit</button>
            {!! Form::submit('Submit', array('class' => 'btn btn-primary btn-fill hidden-xs hidden-sm')) !!}
          </div>
          {!! Form::close() !!}

</div>
