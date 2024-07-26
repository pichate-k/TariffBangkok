$(document).ready(function() {

});

$(document).on("click", "#btn_create_document_nt1", function(event, data) {
    let formDocumentNT1 = new FormData($("#fm_document_nt1")[0]);
    createDocumentNT1(formDocumentNT1);
});

function createDocumentNT1(input) {
    let urlService = "/service/doc/createDocumentNT1";

    let dataService = input;

    callWebServiceTask(enableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataFalse, false, function(success) {
        console.log(success.responseJSON);

        if (success.status == 201) {
            let msg = success.responseJSON.user_message;
            showToast(msg, 'success', 3000);
            clearValidationUI();
            window.location.href = "/u/document/status.htm";
        }
    }, function(fail) {
        console.log(fail.responseJSON);

        let msg = fail.responseJSON.user_message;
        if (fail.status == 400) {
            clearValidationUI();
            for (var text in msg) {
              addValidatetionUI($("#fm_document_nt1").find("[name=" + text + "]"), msg[text]);
            }
        } else if (fail.status == 500) {
            showToast(msg, 'danger', 5000);
        }
    });
}
