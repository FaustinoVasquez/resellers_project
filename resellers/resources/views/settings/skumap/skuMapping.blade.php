@extends('master')

@section('title','SKU Mapping')

@section('css')
  <link href="{{ URL::asset('assets/select2/dist/css/select2.css') }}" rel="stylesheet"/>
  <link href="{{ URL::asset('assets/datatables/datatables.min.css') }}" rel="stylesheet"/>
  <link href="{{ URL::asset('css/settings/settings.css') }}" rel="stylesheet"/>
@endsection

@section('icon','fa-home')
@section('page')
  @include('settings.skumap.partials.modal')
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div id="infoData">
                        @include('settings/skumap/partials/form')
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
  <script src="{{ URL::asset('assets/datatables/datatables.js') }}"></script>

    <script>
    var typingTimer;                //timer identifier
    var doneTypingInterval = 750;  //time in ms, 5 second for example

    $("#name-drop").dropdown();
    $("#button-cart").dropdown();
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

            function autocomplete_element(value, options) {
                var ac = $("<select id='mysku'></select>");
                ac.empty();
                ac.append("<option>Loading...</option>")
                $.get("getSkuList",function(data){
                  ac.empty();
                  ac.append(data);
                })

                ac.val(value);
                return ac;
            }

            function autocomplete_value(elem, op, value) {
                if (op == "set") {
                    $(elem).val(value);
                }
                return $(elem).val();
            }

            myGrid.jqGrid({
                url: 'skuMapSearch',
                styleUI: 'Bootstrap',
                postData: {
                    Search: function () {return jQuery("#mySearch").val();},
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
                editurl:"skuMapEdit",
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
                    var url = 'skuMapSearch?Excel=1&' + inputs;
                    location.href = url;
                }
            });


            var myReload = function () {
                jQuery('#grid').trigger('reloadGrid');
            };

            $("#table-mapping").DataTable({
              ajax: "getSkuList",
              serverSide:true,
              processing: true,
              select: true,
              dom: 'lrtip'
            });

            $("#search").keydown(function(){
              clearTimeout(typingTimer);
            })

            $("#search").keyup(function(){
              clearTimeout(typingTimer);
              typingTimer = setTimeout(doneTyping, doneTypingInterval);
            })

            function doneTyping(){
              var table = $("#table-mapping").DataTable();
              table.search($("#search").val()).draw();
            }

            // Recargar el grid en el evento submit del formulario
            $('#myForm').on('submit', function (e) {
                e.preventDefault();
                myReload();
            });

            $("#add_grid_top").off().on("click",function(){
              $("#modal-sku").modal('show');
              $("input[name='oper']").val("add");
            })

            $("#edit_grid_top").off().on("click",function(){
              var grid = $("#grid");
              selRowId = grid.jqGrid('getGridParam', 'selrow');
              if (selRowId != null) {
                sku = grid.jqGrid('getCell',selRowId,'MITSKU');
                customerSku = grid.jqGrid('getCell',selRowId,'CustomerSKU');
                description = grid.jqGrid('getCell',selRowId,'Description');
                id = grid.jqGrid('getCell',selRowId,'ID');
                $("input[name='CustomerSKU']").val(customerSku);
                $("input[name='MITSKU']").val(sku);
                $("input[name='description']").val(description);
                $("input[name='id']").val(id);
                $("#modal-sku").modal('show');
                $("input[name='oper']").val("edit");
              }
              else{
                gritter("Error","No mapped SKU selected. Please select on SKU to edit","danger");
              }
            })

            $("#modal-sku").on("hidden.bs.modal",function(){
              $("#form-map-sku")[0].reset();
            });

            $("#form-map-sku").submit(function(e){
              e.preventDefault();
              e.stopImmediatePropagation();
              var form = this;
              $.post(this.action,$(this).serialize(),function(data){
                var json = parseJSON(data);
                if(json){
                  if(json.code == 0){
                    gritter("Sku Mapped",json.message,"success");
                    form.reset();
                    $("input[name='oper']").val("add");
                    myReload();
                    if(json.close == 1){
                      $("#modal-sku").modal('hide');
                    }
                  }else{
                    gritter("Error",json.message,"danger");
                  }
                }
                else{
                  console.log(data);
                }
              })
            })

            $('#table-mapping tbody').on( 'click', 'tr', function () {
              var row = this;
              var table = $('#table-mapping').DataTable();
                if ( $(this).hasClass('.info') ) {
                    $(this).removeClass('info');
                }
                else {
                    table.$('tr.info').removeClass('info');
                    $(this).addClass('info');
                }
                $("input[name='MITSKU']").val(table.row(row).data()[0])
                $("input[name='description']").val(table.row(row).data()[1])
            } );

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
