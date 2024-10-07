@extends('master')

@section('title','Purchase Report by SKU')

@section('icon','fa-home')
@section('page')
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    @include('partials/details')
                    <div id="infoData">
                        @include('reports/partials/form')
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
    <script>
        $.jgrid.defaults.width = 1460;
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
                url: 'pBySkuSearch',
                styleUI: 'Bootstrap',
                postData: {
                    Search: function () {return jQuery("#search").val();},
                    DateFrom: function () {return jQuery("#datefrom").val();},
                    DateTo: function () {return jQuery("#dateto").val();},
                },
                mtype: "GET",
                datatype: 'json',
                colNames: [{!!$colNames!!}],
                colModel: [{!!$colModel!!}],
                rowNum: 50,
                pager: '#pager',
                sortname: 'O.OrderNumber',
                viewrecords: true,
                rownumbers: true,
                sortorder: 'desc',
                height: 500,
                shrinkToFit: true,
                autowidth: true,
                toppager: true,
                scroll: 1,
                page: 1,
                scrollPopUp: true,
                scrollLeftOffset: "83%",
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
                    var url = 'pBySkuSearch?Excel=1&' + inputs;
                    location.href = url;
                }
            });

            $('#grid').on('click', '.btn-details', function (e) {
                var id = $(this).data('id');
                var form = $('#form-details');
                var url = form.attr('action').replace(':ORDER_ID', id).replace(':FROM', 'Purchase Report By Sku');
                $.get(url, function (result) {
                    $("#details").html(result)
                    $("#infoData").addClass('hidden');
                    $("#details").removeClass('hidden').show("slow");
                });
                e.preventDefault();
            });

            $('#details').on('click', '.linkClass', function (e) {
                $("#details").addClass('hidden');
                $("#infoData").removeClass('hidden').show("slow");
                e.preventDefault();
            });

            var myReload = function () {
                jQuery('#grid').trigger('reloadGrid');
            };

            // Recargar el grid en el evento submit del formulario
            $('#myForm').on('submit', function (e) {
                e.preventDefault();
                myReload();
            });

            //Resized the jqgrid only if the container is bigger than the necessary width, other way, it adds the overflow-x scroll
            $('.contentpanel').resize(function(){
                console.log('did it');
                var width = $(this).find('.panel-body').width();
                //console.log($(this).find('.panel-body').width());
                if($(window).height() > 800){
                  $('#grid').setGridHeight($(window).height() - 380);
                }
                if(width > 1460){
                    $('#grid').setGridWidth(width);
                }
            })
        });
    </script>
@endsection
