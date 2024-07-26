let queryStringDocumentViewDocNo = new URLSearchParams(window.location.search).get('doc_no');
let queryStringDocumentCreatedDate = new URLSearchParams(window.location.search).get('created_date');

$(document).ready(function() {
  $("#fm_document_comment").find("[name=doc_no]").val(queryStringDocumentViewDocNo);

  $("#fm_document_comment").find("[name=deadline_submit_doc]").datetimepicker({
    icons: {
        next: 'fa-solid fa-right-long',
        previous: 'fa-solid fa-left-long'
    },
    sideBySide: true,
    locale: 'th',
    format: 'YYYY-MM-DD',
    minDate: true,
    minDate: moment().subtract(0,'d').format('YYYY-MM-DD'),
    defaultDate: moment(queryStringDocumentCreatedDate).add(15,'d').format('YYYY-MM-DD'),
  });

});

$(document).on("change", "#fm_document_comment [name=doc_status]", function(event, data) {
  switch (this.value) {
    case "1":
      $("#layout_reject").addClass("d-none");
      $("#layout_other").addClass("d-none");
      break;
    case "11":
      $("#layout_reject").removeClass("d-none");
      $("#layout_other").addClass("d-none");
      break;
    case "12":
      $("#layout_reject").addClass("d-none");
      $("#layout_other").removeClass("d-none");
      break;
  }
});

$(document).on("click", "#btn_save_document_comment_log", function(event, data) {
    let formDocumentComment = new FormData($("#fm_document_comment")[0]);
    createDocumentCommentLogByAdmin(formDocumentComment);
});

function createDocumentCommentLogByAdmin(input) {
    let urlService = "/service/doc/createDocumentCommentLogByAdmin";

    let dataService = input;

    callWebServiceTask(enableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataFalse, false, function(success) {
        console.log(success.responseJSON);

        if (success.status == 201) {
            let msg = success.responseJSON.user_message;
            showToast(msg, 'success', 3000);
            clearValidationUI();
            window.close();
        }
    }, function(fail) {
        console.log(fail.responseJSON);

        let msg = fail.responseJSON.user_message;
        if (fail.status == 400) {
            clearValidationUI();
            for (var text in msg) {
              addValidatetionUI($("#fm_document_comment").find("[name=" + text + "]"), msg[text]);
            }
        } else if (fail.status == 500) {
            showToast(msg, 'danger', 5000);
        }
    });
}
