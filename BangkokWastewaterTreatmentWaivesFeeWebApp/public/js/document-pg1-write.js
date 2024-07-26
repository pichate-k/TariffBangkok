$(document).ready(function() {

});

$(document).on("click", "#btn_create_document_pg1", function(event, data) {
    let formDocumentPG1 = new FormData($("#fm_document_pg1")[0]);
    createDocumentPG1(formDocumentPG1);
});

function createDocumentPG1(input) {
    let urlService = "/service/doc/createDocumentPG1";

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
              addValidatetionUI($("#fm_document_pg1").find("[name=" + text + "]"), msg[text]);
            }
        } else if (fail.status == 500) {
            showToast(msg, 'danger', 5000);
        }
    });
}
