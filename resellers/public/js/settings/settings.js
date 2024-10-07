//Handlers
$("form").submit(function(e){
  e.preventDefault();
  e.stopImmediatePropagation();
  postForm(this);
})

//Function
function postForm(form){
  $.post("usersettings/" + $(form).attr("action"),$(form).serialize(),function(data){
    var json = parseJSON(data);
    if(json){
      if(json.code == 0){
        gritter("Success",json.message,"success");
      }
      else{
        gritter("Error",json.message,"danger");
      }
    }
  });
}
