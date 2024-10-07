@extends('master')

@section('title','Other Customized Price')

@section('page')
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    @include('partials/details')
                    <div id="infoData">
                        @include('pricing/new/partials/form')
                        @include('partials/modalCart')
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
        $.jgrid.defaults.width = 1600;
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
                url: 'newSearch',
                styleUI: 'Bootstrap',
                postData: {
                    Search: function () {return jQuery("#search").val();},
                    Categories: function () {return jQuery("#categories option:selected").val();},
                    Manufacturers: function () {return jQuery("#manufacturers option:selected").val();},
                    Availability: function () { return jQuery("#availability option:selected").val(); },
                },
                mtype: "GET",
                datatype: 'json',
                colNames: [{!!$colNames!!}],
                colModel: [{!!$colModel!!}],
                rowNum: 50,
                pager: '#pager',
                sortname: 'rsp.MIPortalSKU',
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
                },
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
                    var url = 'newSearch?Excel=1&' + inputs;
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
                            gritter("Not in stock.","Please select another product.","danger");
                        }
                    }else{
                          gritter("No item selected.","Please select a row first.","danger");
                    }
                }
            });

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
            $('#' + parentRowID).append("<iframe src ='http://photos.discount-merchant.com/photos/sku/"+parentRowKey+"' width='100%' height=210px frameborder='0'></iframe>");
        }

        //Resized the jqgrid only if the container is bigger than the necessary width, other way, it adds the overflow-x scroll
        $('.contentpanel').resize(function(){
          var width = $(this).find('.panel-body').width();
          if(width > 1490){
            $('#grid').setGridWidth(width);
          }
          if($(window).height() > 800){
            $('#grid').setGridHeight($(window).height() - 380);
          }
        })


        // $(document).on('hover','td',function(){
        //
        // })
    </script>
@endsection

