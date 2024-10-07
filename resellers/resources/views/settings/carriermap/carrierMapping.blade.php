@extends('master')

@section('title','Carrier Mapping')

@section('icon','fa-home')
@section('page')
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div id="infoData">
                        @include('settings/carriermap/partials/form')
                        @include('partials/modal')
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
                url: 'carrierMapSearch',
                styleUI: 'Bootstrap',
                postData: {
                    search: function () {return jQuery("#search").val();},
                },
                mtype: "GET",
                datatype: 'json',
                colNames: [{!!$colNames!!}],
                colModel: [{!!$colModel!!}],
                rowNum: 50,
                pager: '#pager',
                sortname: 'ID',
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
                editurl:"carrierMapEdit",
                    loadOnce:true,
            });


            myGrid.jqGrid('navGrid', '#pager',
                    {cloneToTop: true, add: true, edit: true, del: true, search: false, refresh: false}, //options
                    {   // edit options
                        recreateForm:true,
                        jqModal:false,
                        reloadAfterSubmit:false,
                        closeAfterEdit:true,
                        savekey: [true,13],
                        closeAfterEdit:true,
                        zIndex:1000,
                        editData: {
                            _token: '{{ csrf_token() }}'
                        },
                        beforeShowForm: function(form) { $('#tr_ID', form).hide(); }
                    },
                    {   // add options
                        recreateForm:true,
                        jqModal:false,
                        reloadAfterSubmit:false,
                        closeAfterEdit:true,
                        savekey: [true,13],
                        closeAfterEdit:true,
                        zIndex:1000,
                        editData: {
                            _token: '{{ csrf_token() }}'
                        },
                    },
                    {   // del options
                        reloadAfterSubmit: false,
                        delData: {
                            _token: '{{ csrf_token() }}'
                        },
                    },
                    {} // search options
            );

            myAddButton({
                buttonicon: "fa fa-file-excel-o",
                title: "Excel",
                caption: "Excel",
                position: "last",
                onClickButton: function () {
                    var inputs = $("#myForm").serialize();
                    var url = 'carrierMapSearch?Excel=1&' + inputs;
                    location.href = url;
                }
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
              if(width > 1460){
                $('#grid').setGridWidth(width);
              }
              if($(window).height() > 800){
                $('#grid').setGridHeight($(window).height() - 380);
              }
            })
        });
    </script>
@endsection
