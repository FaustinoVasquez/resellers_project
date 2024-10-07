@extends('master')

@section('title','Order Management Center')

@section('icon','fa-edit')

@section('css')
  <link href="{{ URL::asset('assets/dropzone/dropzone.css') }}" rel="stylesheet">
@endsection

@section('page')
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-body">
          @include('partials/details')
          <div id="infoData">
            @include('orderManagement/om/partials/form')
            @include('orderManagement/om/partials/formUpload')
            @include('partials/modal')
            <table id="grid"></table>
            <div id="pager"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {!! Form::open(['route'=>['orderDetails', ':ORDER_ID/:FROM'], 'method' => 'GET', 'id'=>'form-details']) !!}
  {!! Form::close() !!}

@endsection

@section('scripts')
  <script src="{{ URL::asset('assets/dropzone/dropzone.js') }}"></script>
    <script>
        $.jgrid.defaults.width = 970;
        $.jgrid.styleUI.Bootstrap.base.headerTable = "table table-bordered table-condensed";
        $.jgrid.styleUI.Bootstrap.base.rowTable = "table table-bordered table-condensed table table-striped";
        $.jgrid.styleUI.Bootstrap.base.footerTable = "table table-bordered table-condensed";
        $.jgrid.styleUI.Bootstrap.base.pagerTable = "table table-condensed";

        $(function() {
            var myGrid= $("#grid"),
            myAddButton = function(options) {
                 myGrid.jqGrid('navButtonAdd','#'+myGrid[0].id+"_toppager_left",options);
            };

            myGrid.jqGrid({
                url: 'omSearch',
                styleUI : 'Bootstrap',
                postData: {
                    Search: function() { return jQuery("#search").val(); },
                    Status: function() { return jQuery("#status").val(); },
                    DateFrom: function() { return jQuery("#datefrom").val(); },
                    DateTo: function() { return jQuery("#dateto").val(); },
                },
                mtype: "GET",
                datatype: 'json',
                colNames:[{!!$colNames!!}],
                colModel:[{!!$colModel!!}],
                rowNum: 50,
                pager: '#pager',
                sortname: 'OrderNumber',
                viewrecords: true,
                rownumbers: true,
                sortorder: 'desc',
                height: 500,
                toppager:true,
                shrinkToFit: true,
                autowidth: true,
                scroll: 1,
                page: 1,
                scrollPopUp:true,
                scrollLeftOffset: "83%",
            });

            $('#grid').one('jqGridGridComplete',function(){
              $('.contentpanel').trigger('resize');
            });

            myGrid.jqGrid('navGrid','#pager',
            { cloneToTop:true,add:false,edit:false,del:false,search:false,refresh:false}, //options
            {height:280,reloadAfterSubmit:false}, // edit options
            {height:280,reloadAfterSubmit:false}, // add options
            {reloadAfterSubmit:false}, // del options
            {} // search options
            );

            myAddButton({
                buttonicon: "fa fa-file-excel-o",
                title: "Excel",
                caption: "Excel",
                position: "last",
                onClickButton: function(){
                    var inputs = $("#myForm").serialize();
                    var url = 'omSearch?Excel=1&'+inputs;
                    location.href = url;
                }
            });

            myAddButton({
                buttonicon: "fa fa-file-excel-o",
                title: "CSV",
                caption: "CSV",
                position: "last",
                onClickButton: function(){
                    var inputs = $("#myForm").serialize();
                    var url = 'omSearch?Csv=1&'+inputs;
                    location.href = url;
                }
            });

            myAddButton({
               buttonicon: "fa fa-times",
               title: "Cancel",
               caption: "Cancel",
               position: "last",
               onClickButton: function(){
                   var selRowId = $(grid).jqGrid('getGridParam','selrow');
                   if(selRowId){
                       var orderNumber = $(grid).jqGrid('getCell', selRowId, 'OrderNumber');
                       var resp = $validate($(this),selRowId,orderNumber);
                       if(resp){
                          console.log('cancelOrder/'+orderNumber);
                           $.get('cancelOrder/'+$(orderNumber).text(),null,function(resp){
                             var json = parseJSON(resp);
                            if(json){
                              if(json.code == 0){
                                gritter("Order Cancelled","We sent an email to our team. This could take a moment to process. Please check again later for the status in this same page, in case that the order hasn't been cancelled please, send us an email to ordercancellations@mitechnologiesinc.com",'success');
                              }
                              else{
                                gritter("Error","There was an error cancelling your request. Please, try again or communicate with us to cancel the order directly.",'danger');
                              }
                            }
                           })
                       }else{
                           gritter('Error','This order cannot be cancelled.','danger');
                       }
                   }else{
                       gritter('Error',"Please, select a row first.","danger");
                   }

               }
           });


            $('#grid').on('click', '.btn-details', function (e) {
                window.location.hash = 'orderStatus';
                var id = $(this).data('id');
                var form = $('#form-details');
                var url = form.attr('action').replace(':ORDER_ID', id).replace(':FROM', 'Order Manager');
                $.get(url, function (result) {
                    $("#details").html(result)
                    $("#infoData").addClass('hidden');
                    $("#details").removeClass('hidden').show( "slow" );
                });
                e.preventDefault();
            });

            $('#details').on('click', '.linkClass', function (e) {
                window.history.back();
            });

            var myReload = function() {
                jQuery('#grid').trigger('reloadGrid');
            };

            // Recargar el grid en el evento submit del formulario
            $( '#myForm' ).on( 'submit', function( e ){
                e.preventDefault();
                myReload();
            });


            $('#upForm').on('submit',function(e){
                e.preventDefault();
                var formData = new FormData($(this)[0]);
                $.ajax({
                    type: "POST",
                    url: "attachFile",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (result) {
                        alert(result.message);
                    }
                });
            });

            //Resized the jqgrid only if the container is bigger than the necessary width, other way, it adds the overflow-x scroll
            $('.contentpanel').resize(function(){
              var width = $(this).find('.panel-body').width();
              //console.log($(this).find('.panel-body').width());
              if(width > 970){
                $('#grid').setGridWidth(width);
              }
              if($(window).height() > 800){
                $('#grid').setGridHeight($(window).height() - 380);
              }
            })
        });

        function checkOrderStatus(e) {
            if (location.hash === "") {
              $("#details").addClass('hidden');
              $("#infoData").removeClass('hidden').show("slow");
              e.preventDefault();
            }
        }

        window.onhashchange = checkOrderStatus;

    </script>

@endsection
