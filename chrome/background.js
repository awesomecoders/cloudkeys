function copyLinkToClipboard(link) {
  $("#copyBuffer").val(link);
  var inp = document.getElementById("copyBuffer");
  inp.focus();
  inp.select();
  document.execCommand('copy');
};

chrome.extension.onRequest.addListener(function(obj) {
  copyLinkToClipboard(obj.text);
});
