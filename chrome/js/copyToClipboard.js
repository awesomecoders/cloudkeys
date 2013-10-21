// found this sample on http://stackoverflow.com/questions/13777887/call-background-function-of-chrome-extension-from-a-site

var EVENT_FROM_PAGE = '__rw_chrome_ext_' + new Date().getTime();
var EVENT_REPLY = '__rw_chrome_ext_reply_' + new Date().getTime();

var s = document.createElement('script');
s.textContent = '(' + function(send_event_name, reply_event_name) {
  window.copyToClipboard = function(string) {
    sendMessage({text: string});
  };

  function sendMessage(message) {
    var transporter = document.createElement('dummy');
    transporter.addEventListener(reply_event_name, function(event) {
      var result = this.getAttribute('result');
      if (this.parentNode) this.parentNode.removeChild(this);
    });

    var event = document.createEvent('Events');
    event.initEvent(send_event_name, true, false);
    transporter.setAttribute('data', JSON.stringify(message));
    (document.body||document.documentElement).appendChild(transporter);
    transporter.dispatchEvent(event);
  }
} + ')(' + JSON.stringify(EVENT_FROM_PAGE) + ', ' +
           JSON.stringify(EVENT_REPLY) + ');';

document.documentElement.appendChild(s);
s.parentNode.removeChild(s);

document.addEventListener(EVENT_FROM_PAGE, function(e) {
  var transporter = e.target;
  if (transporter) {
    var message = JSON.parse(transporter.getAttribute('data'));
    chrome.extension.sendRequest(message);
  }
});
