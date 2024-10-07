$(document).ready(function(){
  updateCartBadge();
});

//Handlers----------------------------------------------------------------------

$("#button-cart").click(function(){
  $('#cart-items').empty();
  showLoadingCart();
  $.get('getCartPreview',function(data){
    $("#cart-items").html(data);
  })
  .always(function(){
    hideLoadingCart();
  });
})

//functions---------------------------------------------------------------------
function updateCartBadge(){
  $.get('getCartCount',function(total){
    if(total > 0){
      $("#cart-counter").html('<span class="badge" id="cart-counter">' + total + '</span>')
    }
    else{
      $("#cart-counter").empty();
    }
  })
}

function showLoadingCart(){
  $("#cart-loading").css('display','block');
}

function hideLoadingCart(){
  $("#cart-loading").css('display','none');
}
