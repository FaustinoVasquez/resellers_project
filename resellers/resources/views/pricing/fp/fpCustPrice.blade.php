@extends('master')

@section('title','FP Customized Price')

@section('css')
  <link href="{{ URL::asset('css/dashboard/grid.css') }}" rel="stylesheet"/>
  <link href="{{ URL::asset('css/fp/fp.css') }}" rel="stylesheet"/>
@endsection

@section('page')
    <div class="row full-row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    @include('partials/details')
                    <div id="infoData">
                        @include('pricing/rptv/partials/form')
                        @include('partials/modalCart')
                        @include('partials/email')
                        <table id="grid"></table>
                        <div id="pager"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $.jgrid.defaults.width = 1155;
        $.jgrid.styleUI.Bootstrap.base.headerTable = "table table-bordered table-condensed";
        $.jgrid.styleUI.Bootstrap.base.rowTable = "table table-bordered table-condensed table table-striped";
        $.jgrid.styleUI.Bootstrap.base.footerTable = "table table-bordered table-condensed";
        $.jgrid.styleUI.Bootstrap.base.pagerTable = "table table-condensed";


        $(function () {
 
         var myGrid = $("#grid"),
                    myAddButton = function (options) {
                        myGrid.jqGrid('navButtonAdd', '#' + myGrid[0].id + "_toppager_left", options);
                    };

            myGrid.jqGrid({
                url: 'fpSearch',
                styleUI: 'Bootstrap',
                postData: {
                    Search: function () {return jQuery("#search").val();},
                    Category: function () { return jQuery("#categories option:selected").val(); },
                    Availability: function () { return jQuery("#availability option:selected").val(); },
                },
                mtype: "GET",
                datatype: 'json',
                colNames: [{!!$colNames!!}],
                colModel: [{!!$colModel!!}],
                rowNum: 50,
                pager: '#pager',
                sortname: 'PJD.CatalogID',
                viewrecords: true,
                rownumbers: true,
                sortorder: 'asc',
                height: 500,
                shrinkToFit: true,
                autowidth: true,
                toppager: true,
                scroll: 1,
                page: 1,
                scrollPopUp: true,
                scrollLeftOffset: "83%",
                subGrid: true,
                subGridRowExpanded: showChildGrid,
                subGridOptions:{
                  plusicon: "fa fa-caret-right",
                  minusicon: "fa fa-caret-down",
                  openicon: "fa fa-file"
                }
            });

            $('#grid').one('jqGridGridComplete',function(){
              $('.contentpanel').trigger('resize');
            });

            myGrid.jqGrid('navGrid', '#pager',
                    {cloneToTop: true, add: false, edit: false, del: false, search: false, refresh: false}, //options
                    {height: 280, reloadAfterSubmit: false}, // edit options
                    {height: 280, reloadAfterSubmit: false}, // add options
                    {reloadAfterSubmit: false}, // del options
                    {} // search options
            );

            myAddButton({
                buttonicon: "fa fa-file-excel-o",
                title: "Excel",
                caption: "Excel",
                position: "last",
                onClickButton: function () {
                    var inputs = $("#myForm").serialize();
                    var url = 'fpSearch?Excel=1&' + inputs;
                    location.href = url;
                }
            });

            myAddButton({
                buttonicon: "fa fa-cart-plus",
                title: "Add To Cart",
                caption: "Add To Cart",
                position: "last",
                onClickButton: function () {
                    var selRowId = $(grid).jqGrid('getGridParam','selrow');
                    if(selRowId){
                        var rowData = $(grid).getRowData(selRowId);
                        if($fn_AjaxRequest('validateInStock','sku='+selRowId,"get")){
                            if (!$fn_AjaxRequest('validateDuplicity','sku='+selRowId,"post")) {
                                $fn_AjaxRequest('CreateWebOrder',selRowId,'post');
                                console.log(rowData);
                                if($fn_AjaxRequest('saveOrder',rowData,'post')){
                                  gritter("New Item in Cart","Item Added to your shopping cart.","success");
                                   updateCartBadge();
                                }
                                else{
                                  gritter("Error","There was an error processing your request. Please try again.","danger");
                                }
                            }else{
                              gritter("New Item Quantity","Added +1 to the product in your cart.","success");
                            }
                        }else{
                            gritter("No specified availability.","Please call or email your account manager for information about this item.","danger");
                        }
                    }else{
                          gritter("No item selected.","Please select a row first.","danger");
                    }
                }
            });

            myAddButton({
                buttonicon: "fa fa-envelope-o",
                title: "Send Special Request",
                caption: "Send Special Request",
                position: "last",
                onClickButton: function () {
                  $('#emailModal').modal('show');
                }
            });

            $(document).on("click",".btn-special-request",function(){
              var grid = $("#grid");
              var row = $(this).closest("tr");
              var category = $("td:nth-child(3)",row).text();
              category += " " + $("td:nth-child(4)",row).text();
              category += " " + $("td:nth-child(5)",row).text();
              var sku = this.id;
              var price = $("td:nth-child(7)",row).text();
              var $this = $(this);
              if (!$this.data("bs.popover")) {
                  $this.popover({
                    html: true,
                    content: '<form class="form-special-request"><div class="form-group" style="margin-bottom:10px;"><input type="number" name="quantity" class="form-control"  placeholder="Order Quantity"/></div>'+
                    '<div>'+
                      '<input type="hidden" name="sku" value="' + sku + '">'+
                      '<input type="hidden" name="description" value="' + category + '">'+
                      '<input type="hidden" name="price" value="' + price + '">'+
                      '<div class="btn-group btn-group-justified" style="margin-bottom:0" role="group" aria-label="...">'+
                        '<div class="btn-group" role="group">'+
                          '<button type="submit" class="btn btn-info btn-sm btn-add-sku">Add</button>'+
                        '</div>'+
                        '<div class="btn-group" role="group">'+
                          '<button type="button" class="btn btn-default btn-sm btn-cancel-special-request">Cancel</button>'+
                        '</div>'+
                      '</div>'+
                    '</div>',
                    tigger: 'click',
                    container: 'body',
                    placement: get_popover_placement
                  });
                  //console.log('initialized: '+ this.innerHTML);
                  $this.popover('show');
              }
              $('.btn-special-request').not($this[0]).popover('destroy');
            })

            $(document).on('shown.bs.popover', function () {
              $("input[name='quantity']").focus();
            })

            function get_popover_placement(pop, dom_el) {
              var width = window.innerWidth;
              if (width<500) return 'top';
              var left_pos = $(dom_el).offset().left;
              if (width - left_pos > 400) return 'right';
              return 'left';
            }

            $(document).on('click',".btn-cancel-special-request",function(){
              $('.popover').popover('destroy');
            })

            $(document).on('submit','.form-special-request',function(e){
              e.preventDefault();
              e.stopImmediatePropagation();
              var form = this;
              var sku = $('input[name="sku"]',form).val();
              var quantity = $('input[name="quantity"]',form).val();
              var price = $('input[name="price"]',form).val();
              var description = $("input[name='description']").val();
              if(quantity != "" && quantity > 0){
                $('.popover').popover('hide');
                $("#table-requested").append("<tr><td>" + sku +"</td><td>" + quantity + '</td><td>' + price + '</td><td><button type="button" class="btn btn-block btn-danger btn-remove-request btn-xs">Remove Item</button></td><input type="hidden" name="comment[]" value="' + sku +';' + quantity + ';'+ price + ';' + description + '"/></tr>');
                gritter('Added',"The Sku was added",'success');
              }
              else{
                gritter("Error","The quantity has to be greater than 0","danger")
              }
            })

            $('html').on('click', function(e) {
              if(!$(e.target).hasClass('btn-special-request') && (!$(e.target).parents().is('.popover-content'))){
                $('.btn-special-request').popover("destroy");
              }
            });

            $(document).on('click',".btn-remove-request",function(){
              $(this).closest("tr").remove();
            })

            $("#bcForm").submit(function(e){
              e.preventDefault();
              e.stopImmediatePropagation();
              var form = this;
              $("input[type='submit']").prop("disabled",true);
              var form = this;
              $.post(this.action, $(this).serialize(), function(data){
                var json = parseJSON(data);
                if(json){
                  if(json.code == 0){
                    gritter("Email Sent",json.message,"success");
                    form.reset();
                    $("#table-requested tbody").empty();
                    $("input[name='comment']").val("");
                    $("#emailModal").modal('hide');
                  }
                  else{
                    gritter("Error",json.message,"danger");
                  }
                }
                else{
                  gritter("Error","There was an error processing your request. Please try again.","danger");
                }
              })
              .always(function(){
                $("input[type='submit']").prop("disabled",false);
              })
            })

            var myReload = function () {
                jQuery('#grid').trigger('reloadGrid');
            };

            // Recargar el grid en el evento submit del formulario
            $('#myForm').on('submit', function (e) {
                e.preventDefault();
                myReload();
            });
        });

        function showChildGrid(parentRowID, parentRowKey) {
            var sku = parentRowKey.split("-");
           // var catalogId = sku[0];
	    var catalogId = $(grid).getRowData(parentRowKey)['MITSKU'];
	   //	console.log(mitSku)
 	$('#' + parentRowID).append("<iframe src ='http://photos.discount-merchant.com/photos/sku/"+catalogId+"' width='100%' height=210px frameborder='0'></iframe>");

    //        http://d1vp05nmmxpish.cloudfront.net/catalog/philips/
     //       $.ajax({
      //          url: 'getImages/'+catalogId,
       //         type: "GET",
       //         success: function(html) {
       //             $("#" + parentRowID).append(html);
       //         }
       //     });
        }

        //Resized the jqgrid only if the container is bigger than the necessary width, other way, it adds the overflow-x scroll
        $('.contentpanel').resize(function(){
          var width = $(this).find('.panel-body').width();
          if(width > 1155){
            $('#grid').setGridWidth(width);
          }
          if($(window).height() > 800){
            $('#grid').setGridHeight($(window).height() - 380);
          }
        })

        function $fn_AjaxRequest(_url,_data,_method) {
            var myReturn = false;
            var request = $.ajax({
                url  : _url,
                async: false,
                data : _data,
                method:_method
            });
            request.done(function(ret) {
                var json = parseJSON(ret);
                if(ret){
                  if (json.validation == 1)
                      myReturn = true;
                }
            });
            return myReturn;
        }
    </script>
@endsection
