$(document).ready(function(){
  setup();
})

//handlers----------------------------------------------------------------------
$(document).on('click','.rmv-item',function(){
  deleteItem(this);
})

$('#shipping-method').change(function(){
  changeShipping(this.value);
})

$('#shipping-method').click(function(){
  if(this.value == "Shipping Label Attached"){
    $("#modal-attach-label").modal('show');
  }
})

$(document).on('change','.product-quantity',function(){
  var row = $(this).closest('tr');
  if(this.value > 0){
    updateQuantity(this.id,this.value,row);
  }
  else{
    this.value = 1;
    updateQuantity(this.id,this.value,row);
    gritter("Error","Quantity cannot be 0","danger");
  }
})

$('.btn-rmvOrder').click(function(){
  $("#modal-delete-order").modal('show');
})

$('#btn-del-confirm').click(function() {
    $.post('deleteCart',function(data){
      var json = parseJSON(data);
      if(json){
        if(json.code == 0){
          window.location.href = "checkout";
        }
        else{
          gritter("Error",json.message,"danger");
          $("#modal-delete-order").modal('hide');
        }
      }
    })
});

$("#form-label").submit(function(e){
  e.preventDefault();
  e.stopImmediatePropagation();
  uploadFile(this)
})

$('.btn-process').click(function(){
  $("#form-order").submit();
});
//functions---------------------------------------------------------------------
function setup(){
  $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
  });

  Dropzone.options.formLabel = {
    autoProcessQueue: false,
    addRemoveLinks: true,
    paramName: "shippingLabel",
    uploadMultiple: true,
    parallelUploads:10,
    init: function() {
      var formLabel = this;
      this.on('addedfile',function(file){
        $("#formLabel").removeClass("padded");
      });
      this.on('removedfile',function(file){
        if(this.files.length == 0)
          $("#formLabel").addClass("padded");
      });
      this.on('dragenter',function(file){
        $("#formLabel").removeClass("padded");
      });
      this.on('dragleave',function(file){
        if(this.files.length == 0)
          $("#formLabel").addClass("padded");
      });
      this.on('successmultiple',function(e,response){
        var json = parseJSON(response);
        if(json){
          if(json.code == 0){
            gritter("Success",json.message,"success");
            this.removeAllFiles();
            $("#modal-attach-label").modal('hide');
          }
          else{
            gritter("Error",json.message,"danger");
          }
        }
        else{
          console.log(response);
          gritter("Error","There was an error processing your request. Please try again.","Danger");
        }
      })
      $("#upload-labels").click(function(){
        console.log("click")
        formLabel.processQueue();
      });
    }
  };
}

function deleteItem(button){
  $.post("deleteCartItem",{itemId: button.id},function(data){
    var json = parseJSON(data);
    if(json != false){
      if(json.code == 0){
        gritter("Item deleted",json.message,"success");
        $(button).closest('tr').prev().remove();
        $(button).closest('tr').remove();
        updateCartBadge();
        // $("#".id).parent()..remove();
      }
      else{
        gritter("Error",json.message,"danger");
      }
    }
    else{
      gritter("Error","There was an error processing your request. Please try again.","danger");
    }
  })
}

function changeShipping(value){
  $.post('changeShipping',{ShippingMethod: value},function(data){
    $("#table-holder").html(data);
  })
}

function updateQuantity(id,value,row){
  $.post('updateQuantity',{itemId: id,quantity: value},function(data){
    var json = parseJSON(data);
    if(json.code != undefined && json.code == 1){
      gritter("Error",json.message,"danger");
    }
    else{
      var rowArray = [];
      $('td',row).each(function(){
        rowArray.push(this);
      });
      $(rowArray[3]).text("$" + parseFloat(json.item.ItemUnitShipping).toFixed(2));
      $(rowArray[5]).text("$" + parseFloat(json.item.ItemTotalShipping).toFixed(2));
      $(rowArray[6]).text("$" + parseFloat(json.item.ItemTotal).toFixed(2));
    }
  });
}
