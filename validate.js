var form = document.getElementById('form');
var button = document.getElementById('btnSubmit');
var formElements = form.elements;
var editTextSite = formElements.site;
function onSubmitHandler() {
    var regexp = new RegExp("^(https:\\/\\/|http:\\/\\/)?([www]+\\.)?([A-z0-9\\-]+\\.[A-z]+|\\.[A-z]+){1,4}");//("^(https:\\/\\/|http:\\/\\/)?([www]+\\.)?([A-z0-9]+\\.[A-z]+|\\.[A-z]+){1,4}");
    console.log(regexp.test(editTextSite.value));
    if(!editTextSite.value || !regexp.test(editTextSite.value)) {
        editTextSite.classList.add("error-validate");
        var messageElem = document.createElement('span');
        messageElem.classList.add("error-message");
        messageElem.innerText = "Не валидный url";
        var textWrapper = document.getElementsByClassName("text-input-wrapper");
        console.log(textWrapper.item(0).children.item(1));
        if(textWrapper.item(0).children.item(1)!= null) {
            textWrapper.item(0).removeChild(textWrapper.item(0).children.item(1));
        }
        textWrapper.item(0).appendChild(messageElem);
        return;
    }
    form.submit();
}
button.addEventListener('click', onSubmitHandler);