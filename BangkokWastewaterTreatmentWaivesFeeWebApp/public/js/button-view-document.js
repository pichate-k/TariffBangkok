$(document).on("click", "#btn_view_doc", function(event, data) {

  let dataTable = $('#tb_document_list').DataTable().row($(this).parents('tr')).data();
  let url = "/u/document/view.htm?doc_type=" + dataTable.doc_type + "&doc_no="+ dataTable.doc_no + "&created_date="+ dataTable.created_date;
  let windowName = "mywindow" + new Date().getTime();

  var openWindown = window.open(url, windowName,"width=1100,height=700,scrollbars=yes");
  // updateStatusDocumentReading(dataTable.doc_no, 1);

  var timer = setInterval(function() {
    if(openWindown.closed) {
        clearInterval(timer);
        getDocumentInProcessByAdmin();
        // updateStatusDocumentReading(dataTable.doc_no, 0);
    }
  }, 1000);
});


function updateStatusDocumentReading(docNo, isReading) {
    let urlService = "/service/doc/updateStatusDocumentReading";

    let dataService = {
      "doc_no": docNo,
      "is_reading": isReading
    };

    callWebServiceTask(disableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataTrue, contentTypeApplicationXWwwFormUrlencoded, function(success) {
        console.log(success.responseJSON);

        if (success.status == 201) {
            let msg = success.responseJSON.user_message;

        }
    }, function(fail) {
        console.log(fail.responseJSON);

        let msg = fail.responseJSON.user_message;
        showToast(msg, 'danger', 5000);
    });
}
