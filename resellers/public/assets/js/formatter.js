/**
 * Created by fvasquez on 11/11/15.
 */


$validate = function(grid,selRowId){
    var orderStatus = $(grid).jqGrid('getCell', selRowId, 'OrderStatus');
    var cancelRequest = $(grid).jqGrid('getCell', selRowId, 'CancelRequest');

    if ((orderStatus !== 'Shipped') && (orderStatus !== 'Order Canceled') && ((cancelRequest == null) || (cancelRequest == 0))) {
        return true;
    }else{
        return false;
    }
}



function ajaxSearch(formMethod,formAction,formData){
    $.ajaxSetup({
        headers: {
            'X-XSRF-Token': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        type     : formMethod,
        url      : formAction,
        data     : formData,
        dataType : 'json',
        cache    : false,

        beforeSend : function() {
            console.log(formData);
        },
        success  : function(data) {
            $('.dataTable').html(data);
        },
        error : function(error) {
            $('.dataTable').html(error);
        }
    })
    return false;
};






