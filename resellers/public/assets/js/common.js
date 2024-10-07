/**
 * Created by fvasquez on 12/15/15.
 */

$(function () {
    $('[data-toggle="popover"]').popover();

    $('.datePicker').datepicker({
        daysOfWeekDisabled: "0,6"
    });
});

$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
});


var color = {
    '':'',
    'Shipped':'success',
    'Order Approved':'active',
    'Pending Shipment':'info',
    'Payment Received':'warning',
    'Canceled':'danger',
    'Order Received' : '#CECEF6',
    'Refunded':'#F2F2F2'
};

function orderStatus(cellValue, options, rowObject){
    return "class='"+color[rowObject['OrderStatus']]+"'";
}
function orderStatusInfo(cellValue, options, rowObject){
    return cellValue ? cellValue:'Pending Approval';
}

function orderDetails(cellValue, options, rowObject) {
    return '<a href="" data-id='+cellValue+' class="btn-details">'+cellValue+'</a>';
}


function capitalize(cellValue, options, rowObject){
    return cellValue ? cellValue.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();}):'';
}

function showCellInfo(cellvalue, options,rowData){
    var rowId=options.rowId;
    if (cellvalue!=null){
          return "<input type=\"button\" class=\"btn btn-info btn-xs\" value=\"Show Info\" id="+rowId+" onclick=\"javascript:$showCell('" +escape(cellvalue)+ "',"+rowId+")\" />";
    }else{
        return '';
    }
}

$showCell = function(value,order){
    var mymodal = $("#genericModal");
    mymodal.find('.modal-title').text('Order Instructions to Order: '+ order)
    mymodal.find('.modal-body').text(unescape(value))
    return mymodal.modal('show');
}

function showCellTrk(cellvalue, options,rowData){
    var rowId=options.rowId;
    var orderStatus = rowData.OrderStatus;
    if (orderStatus=='Shipped'){
        return "<input type=\"button\" class=\"btn btn-info btn-xs btn-block btn-trk\" value=\"Tracking Info\" id="+rowId+" />";
    }else{
        return '<input type=\"button\" class=\"btn btn-info btn-xs btn-block\" value=\"No Tracking\" disabled="true" style="margin-right: 15px"/>';
    }
}

$(document).on('click',".btn-trk",function(){
    var value = this.id;
    $.get('getTrackingInformation',{orderNumber: value},function(data){
        var json = parseJSON(data);
        var mymodal = $("#genericModal");
        if(json){
            console.log(json);
            if(json.length > 0){
                for(var i = 0; i < json.length; i++){
                    var link = document.createElement('a');
                    $(link).text(json[i].TrackingID);
                    switch(json[i].Carrier){
                        case 'UPS':
                            $(link).attr("href","http://wwwapps.ups.com/WebTracking/processInputRequest?sort_by=status&tracknums_displayed=1&TypeOfInquiryNumber=T&loc=en_us&InquiryNumber1=" + json[i].TrackingID);
                            $(link).attr('target','_blank');
                            break;
                        case 'USPS':
                            $(link).attr("href","http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?origTrackNum=" + json[i].TrackingID);
                            $(link).attr('target','_blank');
                            break;
                        case 'FDXE':
                            $(link).attr("href","http://www.fedex.com/Tracking?language=english&cntry_code=us&tracknumbers=" + json[i].TrackingID);
                            $(link).attr('target','_blank');
                            break;
                        case 'FXM':
                            $(link).attr("href","http://www.fedex.com/Tracking?language=english&cntry_code=us&tracknumbers=" + json[i].TrackingID);
                            $(link).attr('target','_blank');
                            break;
                        case 'FDXG':
                            $(link).attr("href","http://www.fedex.com/Tracking?language=english&cntry_code=us&tracknumbers=" + json[i].TrackingID);
                            $(link).attr('target','_blank');
                            break;
                        case 'ONTRC':
                            $(link).attr("href","https://www.ontrac.com/trackingres.asp?tracking_number=" + json[i].TrackingID);
                            $(link).attr('target','_blank');
                            break;
                    }
                    mymodal.find('.modal-title').text('Tracking Information for Order: '+ value)
                    mymodal.find('.modal-body').empty();
                    mymodal.find('.modal-body').append(link);
                }
            }
            else{
                mymodal.find('.modal-body').append('<h3 class="text-muted text-center">This order doesn\'t have a tracking number yet.</h3>');
            }
              mymodal.modal('show');
        }
    });
})


function balanceDue(cellValue, options, rowObject){

    if (rowObject['BalanceDue'] == 0) return "class='success'";
    if (rowObject['BalanceDue']  > 0) return "class='danger'";
    if (rowObject['BalanceDue']  < 0) return "class='warning'";
}

function capitalize(cellValue, options, rowObject){
    return cellValue ? cellValue.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();}):'';
}

function reference(cellValue, options, rowObject){
    return cellValue ? cellValue:'No Reference';
}

function checkAvailability(cellValue, options, rowObject){
    var rowId=options.rowId;
    if (cellValue=='Please Call'){
        return "<input type=\"button\" data-toggle=\"popover\" class=\"btn btn-info btn-xs btn-special-request\" value=\"Special Request\" id="+rowId+"  style=\"margin-right:15px; \"/>";
    }else{
        return cellValue;
    }
}
showEmail = function(sku){
    var mymodal = $("#emailModal");
    mymodal.find('.modal-title').text('Avaliability for SKU: '+ sku);
    mymodal.find('.modal-body #subject').val('Avaliability for SKU: '+ sku);
    mymodal.find('.modal-body #sku').val(sku);
    return mymodal.modal('show');
}

$('#name-drop-xs').click(function(){
  $('.menutoggle').click();
});
