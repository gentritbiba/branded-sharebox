function bsCopyToClipboard(obj){
    var bsURLShare =  obj.parentElement.querySelector('.bs-share-url-box-link');
    console.log(bsURLShare);
    bsURLShare.select();
    bsURLShare.setSelectionRange(0, 99999)
    document.execCommand("copy");
    alert("Copied the text: " + bsURLShare.value);
}