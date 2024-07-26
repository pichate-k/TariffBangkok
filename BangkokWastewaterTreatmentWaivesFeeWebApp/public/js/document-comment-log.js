let docCommentLogDocNo = new URLSearchParams(window.location.search).get('doc_no');

$(document).ready(function() {

  $('#tb_document_comment_logs').DataTable({
    paging: false,
    ordering: false,
    searching: false,
    info: false,
    "data": [],
    "columns": [{
        "className": "text-center text-nowrap",
        "render": function(data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        }
    },{
        "data": "doc_status_name",
        "className": "text-nowrap",
        "render": function(data, type, row) {
          if(row.doc_status == 1){
            return "<span class='text-success'>" + row.doc_status_name + "</span>";
          } else if(row.doc_status == 10 || row.doc_status == 11 || row.doc_status == 12) {
            return "<span class='text-danger'>" + row.doc_status_name + "</span>";
          }
        }
    },{
        "data": "comment_date",
        "className": "text-center text-nowrap",
        "render": function(data, type, row) {
          let date = moment(row.comment_date);
          return ((date.isValid()) ? (date.format('DD/MM/') + (parseInt(date.format('YYYY')) + 543) + " " + date.format('HH:mm:ss')) : "-");
        }
    },{
        "data": "username",
        "className": "text-center text-nowrap",
        "visible": ($("#fm_document_comment_detail").find("[name=role_id]").val() != 1),
    },{
        "className": "text-center text-nowrap",
        "render": function(data, type, row) {
          return "<button type='button' class='btn btn-blue-bg btn-sm text-nowrap' id='btn_document_comment_detail'>ดูรายละเอียด</button>";
        }
    }]
  });

  getDocumentCommentLogsByDocNo(docCommentLogDocNo);
});

$(document).on("click", "#btn_document_comment_detail", function(event, data) {
  let dataTable = $('#tb_document_comment_logs').DataTable().row($(this).parents('tr')).data();

  $("#fm_document_comment_detail").find("[name=file_comment_1]").val(dataTable.file_comment_1);
  $("#fm_document_comment_detail").find("[name=file_comment_2]").val(dataTable.file_comment_2);
  $("#fm_document_comment_detail").find("[name=file_comment_3]").val(dataTable.file_comment_3);
  $("#fm_document_comment_detail").find("[name=file_comment_4]").val(dataTable.file_comment_4);
  $("#fm_document_comment_detail").find("[name=file_comment_5]").val(dataTable.file_comment_5);
  $("#fm_document_comment_detail").find("[name=text_comment]").val(dataTable.text_comment);

  let date = moment(dataTable.deadline_submit_doc);
  let deadlineSubmitDoc = ((date.isValid()) ? (date.format('DD/MM/') + (parseInt(date.format('YYYY')) + 543)) : "-");
  $("#fm_document_comment_detail").find("[name=deadline_submit_doc]").text(deadlineSubmitDoc);

  $("#modalDocumentCommentDetail").modal("show");
});

function getDocumentCommentLogsByDocNo(docNo) {
    let urlService = "/service/doc/getDocumentCommentLogsByDocNo";

    let dataService = {
      "doc_no": docNo
    };

    callWebServiceTask(disableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataTrue, contentTypeApplicationXWwwFormUrlencoded, function(success) {
        console.log(JSON.stringify(success.responseJSON));

        if (success.status == 200) {
            let datas = success.responseJSON.results;

            let t = $('#tb_document_comment_logs').DataTable();
            t.clear().draw();
            t.rows.add(datas).draw();
        }

    }, function(fail) {
      console.log(fail.responseJSON);

      $('#tb_document_comment_logs').DataTable().clear().draw();

      let msg = fail.responseJSON.user_message;
      // showToast(msg, 'danger', 5000);
    });
}
