self.addEventListener('message', function(e) {
  httpRequest = new XMLHttpRequest();
  httpRequest.onreadystatechange = function(){
    if (httpRequest.readyState === XMLHttpRequest.DONE) {
      self.postMessage(httpRequest.responseText);
    } else {
        // still not ready
    }
  };
  httpRequest.open('GET', '../../getSkuList', true);
  httpRequest.send(null);
}, false);
