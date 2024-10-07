$("form").submit(function(){
  $("input[type='submit']").attr("disabled","disabled");
  $("#submit-button").append(' <i class="fa fa-spinner fa-spin fa-lg"></i>');
})
