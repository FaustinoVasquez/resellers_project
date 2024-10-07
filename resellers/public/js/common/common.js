function parseJSON(string){
  try{
    var json = JSON.parse(string);
    return json;
  }
  catch(e){
    return false;
  }
}

function gritter(title,message,type){
  jQuery.gritter.add({
    // (string | mandatory) the heading of the notification
    title: title,
    // (string | mandatory) the text inside the notification
    text: message,
    // (bool | optional) if you want it to fade out on its own or just sit there
    sticky: false,
    // (int | optional) the time you want it to be alive for before fading out
    time: '3000',
    class_name: "growl-" + type
   });
}
