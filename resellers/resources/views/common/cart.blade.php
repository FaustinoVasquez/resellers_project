{{--<h3>CustomerID:{{$shipFromInformation['CustomerID']}} WebOrder:{{$webOrder}}</h3>--}}


<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <table id="grid1"></table>
            <div id="pager1"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
        </div>
        <div class="col-md-6">
        </div>
    </div>
</div>

<script>
    $.jgrid.defaults.width = 1460;
    $.jgrid.styleUI.Bootstrap.base.headerTable = "table table-bordered table-condensed";
    $.jgrid.styleUI.Bootstrap.base.rowTable = "table table-bordered table-condensed table table-striped";
    $.jgrid.styleUI.Bootstrap.base.footerTable = "table table-bordered table-condensed";
    $.jgrid.styleUI.Bootstrap.base.pagerTable = "table table-condensed";

    $(function () {
        var myGrid1 = $("#grid1"),
                myAddButton1 = function (options) {
                    myGrid1.jqGrid('navButtonAdd', '#' + myGrid1[0].id + "_toppager_left", options);
                };

        myGrid1.jqGrid({
            url: 'getFullCartGrid',
            styleUI: 'Bootstrap',
            postData: {
                Search: function () {return jQuery("#search").val();},
                Status: function () { return jQuery("#status option:selected").val(); },
                DateFrom: function () {return jQuery("#datefrom").val();},
                DateTo: function () {return jQuery("#dateto").val();},
            },
            mtype: "GET",
            datatype: 'json',
            colNames: [{!!$colNames!!}],
            colModel: [{!!$colModel!!}],
            rowNum: 50,
            pager: '#pager1',
            sortname: 'OrderNumber',
            viewrecords: true,
            rownumbers: true,
            sortorder: 'desc',
            height: 450,
//                shrinkToFit: true,
            //autowidth: true,
            toppager: true,
            scroll: 1,
            page: 1,
            scrollPopUp: true,
            scrollLeftOffset: "83%",
            gridComplete: function(){
                $('.contentpanel').trigger('resize');
            }
        });


        myGrid1.jqGrid('navGrid', '#pager1',
                {cloneToTop: true, add: false, edit: false, del: false, search: false, refresh: false}, //options
                {height: 280, reloadAfterSubmit: false}, // edit options
                {height: 280, reloadAfterSubmit: false}, // add options
                {reloadAfterSubmit: false}, // del options
                {} // search options
        );

        myAddButton1({
            buttonicon: "fa fa-file-excel-o",
            title: "Excel",
            caption: "Excel",
            position: "last",
            onClickButton: function () {
                var inputs = $("#omForm").serialize();
                var url = 'omSearch?Excel=1' + inputs;
                location.href = url;
            }
        });


        var myReload1 = function () {
            jQuery('#grid1').trigger('reloadGrid');
        };

        // Recargar el grid en el evento submit del formulario
        $('#bcForm').on('submit', function (e) {
            e.preventDefault();
            myReload1();
        });

        //Resized the jqgrid only if the container is bigger than the necessary width, other way, it adds the overflow-x scroll
        $('.contentpanel').resize(function(){
            console.log('did it');
            var width = $(this).find('.panel-body').width();
            //console.log($(this).find('.panel-body').width());
            if(width > 1460){
                $('#grid1').setGridWidth(width);
            }
        })
    });
</script>
