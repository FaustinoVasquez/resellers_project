//Global page variables
var blind = {};
//Main function
$(document).ready(function(){

})

//handlers

$("#btn-blind").click(function(){
  if($(this).hasClass("active")){
    displayValues();
  }
  else{
    storeValues();
  }
})

$('.btn-process').click(function(){
  $("#form-shipping-info").submit();
})


//functions
function storeValues(){
  $(".blind-store").each(function(){
    var name = $(this).attr("name");
    var value = $(this).val();
    $(this).val("");
    blind[name] = value;
  });
}

function displayValues(){
  $(".blind-store").each(function(){
    var name = $(this).attr("name");
    $(this).val(blind[name]);
  });
}
